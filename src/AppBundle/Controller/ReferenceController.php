<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chromosome;
use AppBundle\Entity\Locus;
use AppBundle\Entity\Reference;
use AppBundle\Entity\Strain;
use AppBundle\Form\Type\DoiType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReferenceController.
 */
class ReferenceController extends Controller
{
    /**
     * @Route("/reference/add/strain/{slug}", name="reference_add_strain")
     * @Route("/reference/add/locus/{name}", name="reference_add_locus")
     * @Security("is_granted('ROLE_REFERENCER') and ((null != strain and is_granted('VIEW', strain)) or (null != locus and is_granted('VIEW', locus.getChromosome().getStrain())))")
     */
    public function addAction(Strain $strain = null, Locus $locus = null, Request $request)
    {
        $form = $this->createForm(DoiType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doi = $form->getData()['doi'];
            $reference = $this->get('AppBundle\Utils\ReferenceManager')->getReference($doi);

            // Add the Chromosome or Locus to the Reference
            if (null !== $locus) {
                $reference->addLocus($locus);
            } else {
                $reference->addStrain($strain);
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
                return $this->redirectToRoute('strain_view', [
                    'species_slug' => $strain->getSpecies()->getSlug(),
                    'strain_slug' => $strain->getSlug(),
                ]);
            }
        }

        return $this->render('reference/add.html.twig', [
            'form' => $form->createView(),
            'strain' => $strain,
            'locus' => $locus,
        ]);
    }

    /**
     * @Route("/reference/delete/{reference_id}/strain/{strain_id}", name="reference_delete_strain")
     * @Route("/reference/delete/{reference_id}/locus/{locus_id}", name="reference_delete_locus")
     * @ParamConverter("strain", class="AppBundle:Strain", options={
     *   "mapping": {"strain_id": "id"},
     * })
     * @ParamConverter("locus", class="AppBundle:Locus", options={
     *   "mapping": {"locus_id": "id"},
     * })
     * @ParamConverter("reference", class="AppBundle:Reference", options={
     *   "mapping": {"reference_id": "id"},
     * })
     * @Security("is_granted('ROLE_REFERENCER') and ((null != strain and is_granted('VIEW', strain)) or (null != locus and is_granted('VIEW', locus.getChromosome().getStrain())))")
     */
    public function removeAction(Strain $strain = null, Locus $locus = null, Reference $reference, Request $request)
    {
        if (null !== $locus) {
            $redirect = $this->redirectToRoute('locus_view', [
                'species_slug' => $locus->getChromosome()->getStrain()->getSpecies()->getSlug(),
                'strain_slug' => $locus->getChromosome()->getStrain()->getSlug(),
                'chromosome_slug' => $locus->getChromosome()->getslug(),
                'locus_name' => $locus->getName(),
            ]);
        } else {
            $redirect = $this->redirectToRoute('strain_view', [
                'species_slug' => $strain->getSpecies()->getSlug(),
                'strain_slug' => $strain->getSlug(),
            ]);
        }

        // If the CSRF token is invalid, redirect user
        if (!$this->isCsrfTokenValid('reference_delete', $request->get('token'))) {
            $this->addFlash('warning', 'The CSRF token is invalid.');

            return $redirect;
        }

        // Remove the Strain or Locus to the Reference
        if (null !== $locus) {
            $reference->removeLocus($locus);
        } else {
            $reference->removeStrain($strain);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'The reference has been deleted successfully.');

        return $redirect;
    }
}
