<?php
// src/AppBundle/Controller/SpeciesController.php
/**
 * Gestion des espèces.
 *
 * @copyright 2016 DivY
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Species;
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
     * @Route("/{scientificname}", name="species_view")
     */
    public function viewAction($scientificname)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains($scientificname);

        // If there are no species
        if ($species === null) {
            throw $this->createNotFoundException("This species doesn't exists.");
        }

        return $this->render('species/view.html.twig', array(
            'species' => $species,
        ));
    }

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
}
