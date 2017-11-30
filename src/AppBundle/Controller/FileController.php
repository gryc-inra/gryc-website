<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $this->denyAccessUnlessGranted('VIEW', $strain);

        $files = $em->getRepository('AppBundle:FlatFile')->findByStrainFeatureMolFormat($strainName, $featureType, $molType, $format);

        $zip = new \ZipArchive();
        $zipname = '/tmp/'.uniqid();
        $zip->open($zipname, \ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file->getAbsolutePath(), $file->getChromosome()->getName().'-'.$featureType.'-'.$molType.'.'.$format);
        }
        $zip->close();

        $response = new BinaryFileResponse($zipname);
        $response->headers->set('Content-Type', 'application/zip');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $strainName.'-'.$featureType.'-'.$molType.'-'.$format.'.zip');
        $response->headers->set('Cache-Control', 'no-cache');
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
        $file = $this->getDoctrine()->getManager()->getRepository('AppBundle:FlatFile')
            ->findOneByFeatureMolChromosomeFormat($featureType, $molType, $chromosomeName, $format);

        if (null === $file) {
            throw $this->createNotFoundException("This file doesn't exists.");
        }

        $this->denyAccessUnlessGranted('VIEW', $file->getChromosome()->getStrain());

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($file->getAbsolutePath());
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $chromosomeName.'-'.$featureType.'-'.$molType.'.'.$format
        );
        $response->headers->set('X-Accel-Redirect', '/protected-files/flatFiles/'.$file->getPath());

        return $response;
    }
}
