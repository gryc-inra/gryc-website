<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Download controller.
 */
class DownloadController extends Controller
{
    /**
     * @Route("/download", name="download_index")
     */
    public function indexAction()
    {
        $clades = $this->getDoctrine()->getManager()->getRepository('AppBundle:Clade')->getAvailableStrains($this->getUser());

        return $this->render('download/index.html.twig', [
            'clades' => $clades,
        ]);
    }
}
