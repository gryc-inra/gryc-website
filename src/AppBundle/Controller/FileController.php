<?php

// src/AppBundle/Controller/FileController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @param $featureType
     * @param $molType
     * @param $format
     *
     * @throws \Exception
     *
     * @return BinaryFileResponse
     *
     *
     * @Route("/{strainName}-{featureType}-{molType}-{format}.zip", name="file_downloadZipFlatFile")
     */
    public function downloadZipFlatFileAction($strainName, $featureType, $molType, $format)
    {
        $em = $this->getDoctrine()->getManager();

        if (null === $strain = $em->getRepository('AppBundle:Strain')->findOneByName($strainName)) {
            throw $this->createNotFoundException("This strain doen't exists.");
        }
        // If the user have not access to the strain, deny access
        $this->denyAccessUnlessGranted('VIEW', $strain);

        // Get files and create the zip name
        $files = $em->getRepository('AppBundle:FlatFile')->findByStrainFeatureMolFormat($strainName, $featureType, $molType, $format);
        $zipname = $this->get('kernel')->getRootDir().'/../protected-files/temp/'.uniqid().'.zip';

        if (!$zip = new \ZipArchive()) {
            throw new \Exception('The zip file can\'t be create.');
        }

        if ($zip->open($zipname, \ZipArchive::CREATE)) {
            foreach ($files as $file) {
                $zip->addFile($file->getAbsolutePath(), $file->getChromosome()->getName().'-'.$featureType.'-'.$molType.'.'.$format);
            }
            $zip->close();
        } else {
            throw new \Exception('The zip file can\'t be open.');
        }

        // Clear the cache because the file may be send without content when we make it in the same request
        clearstatcache(false, $zipname);

        // Here we don't use the X-Accel-Redirect, because the file isn't static, we delete it just after PHP make it, and nginx take it in charge
        $response = new BinaryFileResponse($zipname);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$strainName.'-'.$featureType.'-'.$molType.'-'.$format.'.zip"');
        $response->headers->set('Cache-Control', 'private');
        // Delete the file
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * Serve flat file.
     * The method return a xaccel header, then nginx serve the file after control by the controller.
     *
     * @param Request $request
     * @param $chromosomeName
     * @param $featureType
     * @param $molType
     * @param $format
     *
     * @return BinaryFileResponse
     *
     * @Route("/{chromosomeName}-{featureType}-{molType}.{format}", name="file_downloadFlatFile")
     */
    public function downloadFlatFileAction(Request $request, $chromosomeName, $featureType, $molType, $format)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AppBundle:FlatFile')->findOneByFeatureMolChromosomeFormat($featureType, $molType, $chromosomeName, $format);

        if (null === $file) {
            throw $this->createNotFoundException("This file doen't exists.");
        }

        $this->denyAccessUnlessGranted('VIEW', $file->getChromosome()->getStrain());

        $request->headers->set('X-Sendfile-Type', 'X-Accel-Redirect');
        $request->headers->set('X-Accel-Mapping', '/var/www/html/current/protected-files/=/protected_files/');

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($file->getAbsolutePath());
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$chromosomeName.'-'.$featureType.'-'.$molType.'.'.$format.'"');
        $response->headers->set('Cache-Control', 'private');

        return $response;
    }
}
