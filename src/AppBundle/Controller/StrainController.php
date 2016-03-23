<?php
// src/AppBundle/Controller/StrainController.php
/**
 * Gestion des souches.
 *
 * @copyright 2016 DivY
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class StrainController extends Controller
{
    /**
     * @Route("species/{speciesSlug}/{strainSlug}", name="strain_view")
     */
    public function viewAction($strainSlug)
    {
        $em = $this->getDoctrine()->getManager();
        //$strain = $em->getRepository('AppBundle:Strain')->getStrainWithSpeciesAndChromosomes($name);
        $strain = $em->getRepository('AppBundle:Strain')->getStrainWithFlatFiles($strainSlug);

        // If there are no strain
        if ($strain === null) {
            throw $this->createNotFoundException("This strain doesn't exists.");
        }

        return $this->render('strain/view.html.twig', array(
            'strain' => $strain,
        ));
    }
}
