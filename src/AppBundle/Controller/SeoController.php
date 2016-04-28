<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use AppBundle\Entity\Strain;
use AppBundle\Form\Type\SpeciesSeoType;
use AppBundle\Form\Type\StrainSeoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/", name="seo_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->findAllWithSeo();

        return $this->render('seo/index.html.twig', array(
            'speciesList' => $species,
        ));
    }

    /**
     * @Route("/species/{slug}", name="seo_species")
     */
    public function speciesAction(Species $species, Request $request)
    {
        $form = $this->createForm(SpeciesSeoType::class, $species);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Edit',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The SEOs were edited !');

            return $this->redirectToRoute('seo_homepage');
        }

        return $this->render('seo/species.html.twig', array(
            'form' => $form->createView(),
            'species' => $species,
        ));
    }

    /**
     * @Route("/strain/{slug}", name="seo_strain")
     */
    public function strainAction(Strain $strain, Request $request)
    {
        $form = $this->createForm(StrainSeoType::class, $strain);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Edit',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The SEOs were edited !');

            return $this->redirectToRoute('seo_homepage');
        }

        return $this->render('seo/strain.html.twig', array(
            'form' => $form->createView(),
            'strain' => $strain,
        ));
    }
}
