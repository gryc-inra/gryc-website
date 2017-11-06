<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chromosome;
use AppBundle\Entity\Locus;
use AppBundle\Entity\Reference;
use AppBundle\Form\Type\DoiType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReferenceController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/reference/add", name="reference_add_chromosome")
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/{locus_name}/reference/add", name="reference_add_locus")
     * @ParamConverter("chromosome", class="AppBundle:Chromosome", options={
     *   "mapping": {"chromosome_slug": "slug"},
     * })
     * @ParamConverter("locus", class="AppBundle:Locus", options={
     *   "mapping": {"locus_name": "name"},
     * })
     * @Security("is_granted('VIEW', chromosome.getStrain())")
     */
    public function addAction(Chromosome $chromosome, Locus $locus = null, Request $request)
    {
        // Check we have the available data
        $routeName = $request->get('_route');
        if ('reference_add_locus' === $routeName && null === $locus) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DoiType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doi = $form->getData()['doi'];
            $reference = $this->get('AppBundle\Utils\ReferenceManager')->getReference($doi);

            // Add the Chromosome or Locus to the Reference
            if (null !== $locus) {
                $reference->addLocus($locus);
            } else {
                $reference->addChromosome($chromosome);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($reference);
            $em->flush();

            $this->addFlash('success', 'The reference has been successfully added.');

            // Redirect to the Locus or chromosome
            if (null !== $locus) {
                return $this->redirectToRoute('locus_view', [
                    'species_slug' => $locus->getChromosome()->getStrain()->getSpecies()->getSlug(),
                    'strain_slug' => $locus->getChromosome()->getStrain()->getSlug(),
                    'chromosome_slug' => $locus->getChromosome()->getslug(),
                    'locus_name' => $locus->getName(),
                ]);
            } else {
                return $this->redirectToRoute('chromosome_view', [
                    'species_slug' => $chromosome->getStrain()->getSpecies()->getSlug(),
                    'strain_slug' => $chromosome->getStrain()->getSlug(),
                    'chromosome_slug' => $chromosome->getslug(),
                ]);
            }
        }

        return $this->render('reference/add.html.twig', [
            'form' => $form->createView(),
            'chromosome' => $chromosome,
            'locus' => $locus,
        ]);
    }

    /**
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/reference/{reference_id}/delete", name="reference_delete_chromosome")
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}/{locus_name}/reference/{reference_id}/delete", name="reference_delete_locus")
     * @ParamConverter("chromosome", class="AppBundle:Chromosome", options={
     *   "mapping": {"chromosome_slug": "slug"},
     * })
     * @ParamConverter("locus", class="AppBundle:Locus", options={
     *   "mapping": {"locus_name": "name"},
     * })
     * @ParamConverter("reference", class="AppBundle:Reference", options={
     *   "mapping": {"reference_id": "id"},
     * })
     * @Security("is_granted('VIEW', chromosome.getStrain())")
     */
    public function removeAction(Chromosome $chromosome, Locus $locus = null, Reference $reference, Request $request)
    {
        // Check we have the available data
        $routeName = $request->get('_route');
        if ('reference_delete_locus' === $routeName && null === $locus) {
            throw $this->createNotFoundException();
        }

        if (null !== $locus) {
            $redirect = $this->redirectToRoute('locus_view', [
                'species_slug' => $locus->getChromosome()->getStrain()->getSpecies()->getSlug(),
                'strain_slug' => $locus->getChromosome()->getStrain()->getSlug(),
                'chromosome_slug' => $locus->getChromosome()->getslug(),
                'locus_name' => $locus->getName(),
            ]);
        } else {
            $redirect = $this->redirectToRoute('chromosome_view', [
                'species_slug' => $chromosome->getStrain()->getSpecies()->getSlug(),
                'strain_slug' => $chromosome->getStrain()->getSlug(),
                'chromosome_slug' => $chromosome->getslug(),
            ]);
        }

        // If the CSRF token is invalid, redirect user
        if (!$this->isCsrfTokenValid('reference_delete', $request->request->get('token'))) {
            $this->addFlash('warning', 'The CSRF token is invalid.');

            return $redirect;
        }

        // Add the Chromosome or Locus to the Reference
        if (null !== $locus) {
            $reference->removeLocus($locus);
        } else {
            $reference->removeChromosome($chromosome);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'The reference has been deleted successfully.');

        return $redirect;
    }
}
