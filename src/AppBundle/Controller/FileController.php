<?php
// src/AppBundle/Controller/FileController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FileController extends Controller
{
    /**
     * @Route("files/{species}/{strain}/{featureType}/{molType}/{chromosome}.{format}", name="file_downloadFlatFile")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function downloadFlatFileAction(Request $request, $featureType, $molType, $chromosome, $format)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AppBundle:FlatFile')->findOneByFeatureMolChromosomeFormat($featureType, $molType, $chromosome, $format);

        $request->headers->set('X-Sendfile-Type', 'X-Accel-Redirect');
        $request->headers->set('X-Accel-Mapping', '/home/docker/protected-files/=/protected_files/');

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($file->getAbsolutePath());
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$chromosome.'-'.$featureType.'-'.$molType.'.'.$format.'"');
        $response->headers->set('Cache-Control', 'private');

        return $response;
    }

    /**
     * @Route("files/{species}/{strain}/{featureType}-{molType}-{format}.zip", name="file_downloadZipFlatFile")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function  downloadZipFlatFileAction(Request $request, $strain, $featureType, $molType, $format)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('AppBundle:FlatFile')->findByStrainFeatureMolFormat($strain, $featureType, $molType, $format);

        $zipname = $this->get('kernel')->getRootDir().'/../protected-files/temp/'.uniqid().'.zip';

        if (!$zip = new \ZipArchive()) {
            throw new \Exception('The zip file can\'t be create.');
        }

        if ($zip->open($zipname,\ZipArchive::CREATE)) {
            foreach ($files as $file) {
                $zip->addFile($file->getAbsolutePath(), $file->getChromosome()->getName() . '-' . $featureType . $molType . '.' . $format);
            }
            $zip->close();
        } else {
            throw new \Exception('The zip file can\'t be open.');
        }

        // Clear the cache because the file may be send without content when we make it in the same request
        clearstatcache(false, $zipname);

        // Here we don't use the X-Accel-Redirect, because the file isn't static, we delete it just after PHP make it, and nginx take it in charge
        $response = new BinaryFileResponse($zipname);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$strain.'-'.$featureType.'-'.$molType.'-'.$format.'.zip"');
        $response->headers->set('Cache-Control', 'private');
        // Delete the file
        $response->deleteFileAfterSend(true);

        return $response;
    }
}
