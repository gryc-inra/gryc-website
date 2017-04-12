<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chromosome;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Locus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocusController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/{locus_name}", name="feature_view")
     * @Route("/locus/{locus_name}")
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

        dump($locus);

        return $this->render('locus/view.html.twig', [
           'locus' => $locus,
        ]);
    }

    //condition="request.isXmlHttpRequest()",

    /**
     * @Route("/locus/{locus_name}/sequence/{feature_name}", options={"expose"=true}, name="feature_sequence")
     * @ParamConverter("feature", class="AppBundle:Feature", options={
     *   "mapping": {"feature_name": "name"},
     *   "map_method_signature" = true
     * })
     */
    public function sequenceAction(Feature $feature, Request $request)
    {
        dump($request->get('upstream'));

        $upstream = !empty($request->get('upstream')) ? $request->get('upstream') : null;
        $downstream = !empty($request->get('downstream')) ? $request->get('downstream') : null;

        return $this->render('locus/sequence.html.twig', [
            'feature' => $feature,
            'upstream' => $upstream,
            'downstream' => $downstream,
        ]);
    }
}
