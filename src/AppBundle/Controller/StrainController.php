<?php
// src/AppBundle/Controller/StrainController.php
/**
 * Gestion des souches.
 *
 * @copyright 2016 DivY
 */
namespace Grycii\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Classe gérant la partie souche.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 *
 * @Route("species/{species}")
 */
class StrainController extends Controller
{
    /**
     * @Route("/{name}", name="strain_view")
     */
    public function viewAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $strain = $em->getRepository('AppBundle:Strain')->getStrainWithSpeciesAndChromosomes($name);

        // If there are no strain
        if ($strain === null) {
            throw $this->createNotFoundException("This strain doesn't exists.");
        }

        return $this->render('strain/view.html.twig', array(
            'strain' => $strain,
        ));
    }
}
