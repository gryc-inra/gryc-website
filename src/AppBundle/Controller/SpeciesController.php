<?php
// src/AppBundle/Controller/SpeciesController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/species")
 */
class SpeciesController extends Controller
{
    /**
     * @Route("/", name="species_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $speciesList = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains(null, $this->getUser());

        return $this->render('species/list.html.twig', array(
            'speciesList' => $speciesList,
        ));
    }

    /**
     * @Route("/{speciesSlug}", name="species_view")
     */
    public function viewAction($speciesSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains($speciesSlug, $this->getUser());

        if (null === $species) {
            throw $this->createNotFoundException("This species doen't exists.");
        }

        return $this->render('species/view.html.twig', array(
            'species' => $species,
        ));
    }
}
