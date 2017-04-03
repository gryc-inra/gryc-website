<?php

// src/AppBundle/Controller/SpeciesController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Species controller.
 */
class SpeciesController extends Controller
{
    /**
     * List species authorized for the user.
     *
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
     * View a species.
     *
     * @Route("/species/{slug}", name="species_view")
     */
    public function viewAction(Species $species)
    {
        return $this->render('species/view.html.twig', [
            'species' => $species,
        ]);
    }
}
