<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Species controller.
 */
class SpeciesController extends Controller
{
    /**
     * @Route("/species", name="species_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $speciesList = $em->getRepository('AppBundle:Species')->getAvailableSpeciesAndStrains($this->getUser());

        return $this->render('species/index.html.twig', [
            'speciesList' => $speciesList,
        ]);
    }

    /**
     * @Route("/species/{slug}", name="species_view")
     */
    public function viewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesAndAvailableStrains($slug, $this->getUser());

        if (null === $species) {
            return $this->createNotFoundException();
        }

        return $this->render('species/view.html.twig', [
            'species' => $species,
        ]);
    }
}
