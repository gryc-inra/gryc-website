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
     * @Route("/db/{species_slug}", name="species_view")
     * @Route("/species/{species_slug}")
     */
    public function viewAction($species_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesAndAvailableStrains($species_slug, $this->getUser());

        if (null === $species) {
            return $this->createNotFoundException();
        }

        return $this->render('species/view.html.twig', [
            'species' => $species,
        ]);
    }
}
