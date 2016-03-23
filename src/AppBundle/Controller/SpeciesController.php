<?php
// src/AppBundle/Controller/SpeciesController.php
/**
 * Gestion des espèces.
 *
 * @copyright 2016 DivY
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Classe gérant la partie espèce.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 *
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
        $speciesList = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains();

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
        $species = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains($speciesSlug);

        // If there are no species
        if ($species === null) {
            throw $this->createNotFoundException("This species doesn't exists.");
        }

        return $this->render('species/view.html.twig', array(
            'species' => $species,
        ));
    }
}
