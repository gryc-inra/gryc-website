<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FlatFile;
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
     * @return BinaryFileResponse
     *
     * @Route("/{strainName}-{type}.zip", name="file_downloadZipFlatFile")
     */
    public function downloadZipFlatFileAction($strainName, $type)
    {
        $em = $this->getDoctrine()->getManager();

        // If the Strain exists ?
        if (null === $strain = $em->getRepository('AppBundle:Strain')->findOneByName($strainName)) {
            throw $this->createNotFoundException("This strain doen't exists.");
        }

        // User is allowed to access the resource ?
        $this->denyAccessUnlessGranted('VIEW', $strain);

        // Retrieve Files
        $files = $em->getRepository('AppBundle:FlatFile')->findByStrainAndType($strainName, $type);

        // Use file manager to get AbsolutePath
        $fileManager = $this->get('AppBundle\Service\FileManager');

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
     * @Route("/{slug}", name="file_downloadFlatFile")
     * @Security("is_granted('VIEW', file.getChromosome().getStrain())")
     */
    public function downloadFlatFileAction(FlatFile $file)
    {
        // Use file manager to get AbsolutePath and SendFilePath
        $fileManager = $this->get('AppBundle\Service\FileManager');

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
