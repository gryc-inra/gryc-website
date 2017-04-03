<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Strain;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Strain controller.
 */
class StrainController extends Controller
{
    /**
     * @Route("species/{speciesSlug}/{strainSlug}", name="strain_view")
     * @ParamConverter("strain", class="AppBundle:Strain", options={
     *     "repository_method": "getStrainWithFlatFiles",
     *     "mapping": {"strainSlug": "slug"},
     *     "map_method_signature" = true
     * })
     * @Security("is_granted('VIEW', strain)")
     */
    public function viewAction(Strain $strain)
    {
        return $this->render('strain/view.html.twig', [
            'strain' => $strain,
        ]);
    }
}
