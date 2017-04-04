<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chromosome;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChromosomeController extends Controller
{
    /**
     * @Route("/chromosome/{slug}", name="chromosome_view")
     * @ParamConverter("chromosome", class="AppBundle:Chromosome", options={
     *     "repository_method" = "getChromosomeWithStrainAndSpecies",
     *     "mapping": {"slug": "slug"},
     *     "map_method_signature" = true
     * })
     * @Security("is_granted('VIEW', chromosome.getStrain())")
     */
    public function viewAction(Chromosome $chromosome)
    {
        return $this->render('chromosome/view.html.twig', [
           'chromosome' => $chromosome,
        ]);
    }
}
