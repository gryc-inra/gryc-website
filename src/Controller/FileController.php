<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use App\Entity\FlatFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * File serving controller.
 *
 * @route("/files")
 */
class FileController extends Controller
{
    /**
     * Serve flat files in a zip.
     * This method create and serve the file.
     *
     * @param $strainName
     * @param $type
     *
     *
     * @Route("/{strainName}-{type}.zip", methods={"GET"}, name="file_downloadZipFlatFile")
     */
    public function downloadZipFlatFileAction($strainName, $type): BinaryFileResponse
    {
        $em = $this->getDoctrine()->getManager();

        // If the Strain exists ?
        if (null === $strain = $em->getRepository('App:Strain')->findOneByName($strainName)) {
            throw $this->createNotFoundException("This strain doen't exists.");
        }

        // User is allowed to access the resource ?
        $this->denyAccessUnlessGranted('VIEW', $strain);

        // Retrieve Files
        $files = $em->getRepository('App:FlatFile')->findByStrainAndType($strainName, $type);

        // Use file manager to get AbsolutePath
        $fileManager = $this->get('App\Service\FileManager');

        // Create a Zip archive
        $zip = new \ZipArchive();
        $zipname = '/tmp/'.uniqid();
        $zip->open($zipname, \ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($fileManager->getAbsolutePath($file), $file->getSlug());
        }
        $zip->close();

        // Return a response
        $response = new BinaryFileResponse($zipname);
        $response->headers->set('Content-Type', 'application/zip');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $strainName.'-'.$type.'.zip');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Serve flat file.
     * The method return a X-Accel header, then nginx serve the file after control by the controller.
     *
     * @Route("/{slug}", methods={"GET"}, name="file_downloadFlatFile")
     * @Security("is_granted('VIEW', file.getChromosome().getStrain())")
     */
    public function downloadFlatFileAction(FlatFile $file)
    {
        // Use file manager to get AbsolutePath and SendFilePath
        $fileManager = $this->get('App\Service\FileManager');

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($fileManager->getAbsolutePath($file));
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getSlug()
        );
        $response->headers->set('X-Accel-Redirect', $fileManager->getSendFilePath($file));

        return $response;
    }
}
