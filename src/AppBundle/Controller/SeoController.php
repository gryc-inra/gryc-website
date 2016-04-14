<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Seo controller.
 *
 * @Route("/seo")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SeoController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/", name="seo_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->findAllWithSeo();
        
        return $this->render('seo/index.html.twig', array(
            'speciesList' => $species,
        ));
    }
}
