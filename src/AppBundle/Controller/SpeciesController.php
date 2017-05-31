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
     * @Route("/db/{species_slug}", name="species_view")
     * @Route("/species/{species_slug}")
     */
    public function viewAction($species_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesAndAvailableStrains($species_slug, $this->getUser());

        if (null === $species) {
            throw $this->createNotFoundException();
        }

        return $this->render('species/view.html.twig', [
            'species' => $species,
        ]);
    }
}
