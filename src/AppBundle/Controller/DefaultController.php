<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Static pages controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * Homepage.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $species = $em->getRepository('AppBundle:Species')->getAvailableSpeciesAndStrains($this->getUser());

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'speciesList' => $species,
        ]);
    }

    /**
     * Privacy policy page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/privacy-policy", name="privacy-policy")
     */
    public function privacyPolicyAction()
    {
        return $this->render('default/privacyPolicy.html.twig');
    }
}
