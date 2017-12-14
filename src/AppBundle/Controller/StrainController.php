<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Strain;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Strain controller.
 */
class StrainController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}", name="strain_view")
     * @Route("strain/{strain_slug}")
     * @Entity("strain", expr="repository.getStrainWithFlatFiles(strain_slug)")
     * @Security("is_granted('VIEW', strain)")
     */
    public function viewAction(Strain $strain)
    {
        return $this->render('strain/view.html.twig', [
            'strain' => $strain,
        ]);
    }

    /**
     * @Route("/strain", name="strain_index")
     */
    public function listAction()
    {
        $clades = $this->getDoctrine()->getManager()->getRepository('AppBundle:Clade')->getAvailableStrains($this->getUser());

        return $this->render('strain/index.html.twig', [
            'clades' => $clades,
        ]);
    }
}
