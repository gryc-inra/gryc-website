<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use AppBundle\Entity\Strain;
use AppBundle\Form\Type\SpeciesSeoType;
use AppBundle\Form\Type\StrainSeoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Seo controller.
 *
 * @Route("/admin/seo")
 */
class SeoController extends Controller
{
    /**
     * @Route("/", name="seo_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->findAllWithSeo();

        return $this->render('seo/index.html.twig', [
            'speciesList' => $species,
        ]);
    }

    /**
     * @Route("/species/{slug}", name="seo_species")
     */
    public function speciesAction(Species $species, Request $request)
    {
        $form = $this->createForm(SpeciesSeoType::class, $species);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The SEOs were edited !');

            return $this->redirectToRoute('seo_homepage');
        }

        return $this->render('seo/species.html.twig', [
            'form' => $form->createView(),
            'species' => $species,
        ]);
    }

    /**
     * @Route("/strain/{slug}", name="seo_strain")
     */
    public function strainAction(Strain $strain, Request $request)
    {
        $form = $this->createForm(StrainSeoType::class, $strain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The SEOs were edited !');

            return $this->redirectToRoute('seo_homepage');
        }

        return $this->render('seo/strain.html.twig', [
            'form' => $form->createView(),
            'strain' => $strain,
        ]);
    }
}
