<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Locus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocusController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/{locus_name}", name="locus_view")
     * @Route("/locus/{locus_name}", name="locus_view_short")
     */
    public function viewAction($locus_name)
    {
        $em = $this->getDoctrine()->getManager();
        // Get the Locus, first try LocusName, then FeatureName, then ProductName
        if (null === $locus = $em->getRepository('AppBundle:Locus')->findLocus($locus_name)) {
            if (null === $locus = $em->getRepository('AppBundle:Locus')->findLocusFromFeature($locus_name)) {
                if (null !== $locus = $em->getRepository('AppBundle:Locus')->findLocusFromProduct($locus_name)) {
                } else {
                    throw $this->createNotFoundException('This locus doesn\'t exists.');
                }
            }
        }

        $this->denyAccessUnlessGranted('VIEW', $locus->getChromosome()->getStrain());

        return $this->render('locus/view.html.twig', [
           'locus' => $locus,
        ]);
    }

    /**
     * @Route("/locus/{locus_name}/sequence/{feature_name}", options={"expose"=true}, condition="request.isXmlHttpRequest()", name="feature_sequence")
     * @ParamConverter("feature", class="AppBundle:Feature", options={
     *   "mapping": {"feature_name": "name"},
     *   "map_method_signature" = true
     * })
     */
    public function sequenceAction(Feature $feature, Request $request)
    {
        // Display UTR or/and Introns ?
        $showUtr = !empty($request->get('showUtr')) ? filter_var($request->get('showUtr'), FILTER_VALIDATE_BOOLEAN) : true;
        $showIntron = !empty($request->get('showIntron')) ? filter_var($request->get('showIntron'), FILTER_VALIDATE_BOOLEAN) : true;

        // Get UP and DOwnstream
        $upstream = !empty($request->get('upstream')) ? $request->get('upstream') : null;
        $downstream = !empty($request->get('downstream')) ? $request->get('downstream') : null;

        // If Upstream and/or downstream defined, showUtr and showIntron on Yes
        if (null !== $upstream || null !== $downstream) {
            $showUtr = true;
            $showIntron = true;
        }

        return $this->render('locus/sequence.html.twig', [
            'feature' => $feature,
            'showUtr' => $showUtr,
            'showIntron' => $showIntron,
            'upstream' => $upstream,
            'downstream' => $downstream,
        ]);
    }
}
