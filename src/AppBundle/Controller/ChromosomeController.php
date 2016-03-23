<?php
// src/AppBundle/Controller/ChromosomeController.php
/**
 * Gestion des chromosomes.
 *
 * @copyright 2016 DivY
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Classe gérant la partie chromosome.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 *
 * @Route("chromosome")
 */
class ChromosomeController extends Controller
{
    /**
     * @Route("/{slug}", name="chromosome_view")
     */
    public function viewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $chromosome = $em->getRepository('AppBundle:Chromosome')->getChromosomeWithStrainAndSpecies($slug);

        // If there are no chromosome
        if ($chromosome === null) {
            throw $this->createNotFoundException("This chromosome doesn't exists.");
        }

        return $this->render('chromosome/view.html.twig', array(
           'chromosome' => $chromosome,
        ));
    }
}
