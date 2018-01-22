<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Locus;
use AppBundle\Form\Type\FeatureDynamicSequenceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocusController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/{locus_slug}", name="locus_view")
     * @Entity("locus", expr="repository.findLocusWithAllData(locus_slug)")
     * @Security("is_granted('VIEW', locus.getChromosome().getStrain())")
     */
    public function viewAction(Locus $locus, Request $request)
    {
        $forms = [];
        $sequences = [];
        foreach ($locus->getFeatures() as $feature) {
            // Init form
            $forms[$feature->getName()] = $this->get('form.factory')->createNamed('feature_dynamic_sequence_'.$feature->getName(), FeatureDynamicSequenceType::class);

            // Handle form
            $forms[$feature->getName()]->handleRequest($request);

            // Valid form ?
            if ($forms[$feature->getName()]->isSubmitted() && $forms[$feature->getName()]->isValid()) {
                $data = $forms[$feature->getName()]->getData();

                $sequences[$feature->getName()] = $feature->getSequence($data['showUtr'], $data['showIntron'], $data['upstream'], $data['downstream']);
            } else {
                $sequences[$feature->getName()] = $feature->getSequence();
            }

            // Create form view
            $forms[$feature->getName()] = $forms[$feature->getName()]->createView();
        }

        return $this->render('locus/view.html.twig', [
            'locus' => $locus,
            'forms' => $forms,
            'sequences' => $sequences,
        ]);
    }

    /**
     * @Route("/locus/{locus_name}", name="locus_view_short")
     */
    public function redirectAction($locus_name)
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

        if (!$this->isGranted('VIEW', $locus->getChromosome()->getStrain())) {
            throw $this->createAccessDeniedException();
        }

        return $this->redirectToRoute('locus_view', [
            'species_slug' => $locus->getChromosome()->getStrain()->getSpecies()->getSlug(),
            'strain_slug' => $locus->getChromosome()->getStrain()->getSlug(),
            'chromosome_slug' => $locus->getChromosome()->getSlug(),
            'locus_name' => $locus->getName(),
        ]);
    }
}
