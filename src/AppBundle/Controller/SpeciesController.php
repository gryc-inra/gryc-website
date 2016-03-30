<?php
// src/AppBundle/Controller/SpeciesController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use AppBundle\Form\Type\SpeciesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

class SpeciesController extends Controller
{
    /**
     * @Route("/species", name="species_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $speciesList = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains(null, $this->getUser());

        return $this->render('species/index.html.twig', array(
            'speciesList' => $speciesList,
        ));
    }

    /**
     * @Route("/species/{speciesSlug}", name="species_view")
     */
    public function viewAction($speciesSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains($speciesSlug, $this->getUser());

        if (null === $species) {
            throw $this->createNotFoundException("This species doen't exists.");
        }

        return $this->render('species/view.html.twig', array(
            'species' => $species,
        ));
    }

    /**
     * @Route("/admin/species/list", name="species_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->getSpeciesWithStrains();

        return $this->render('species/list.html.twig', array(
            'speciesList' => $species,
        ));
    }

    /**
     * @Route("/admin/species/add", name="species_add")
     */
    public function addAction(Request $request)
    {
        $species = new Species();

        $form = $this->createForm(SpeciesType::class, $species);
        $form->add('save', SubmitType::class, array(
            'label' => 'Add a species',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($species);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The species was successfully added.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/species/{id}/edit", name="species_edit")
     */
    public function editAction(Request $request, Species $species)
    {
        $form = $this->createForm(SpeciesType::class, $species);
        $form->add('save', SubmitType::class, array(
            'label' => 'Edit the species',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The species was successfully edited.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/edit.html.twig', array(
            'species' => $species,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/species/{id}/delete", name="species_delete")
     */
    public function deleteAction(Request $request, Species $species)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, array(
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($species);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The species was successfully deleted.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/delete.html.twig', array(
            'species' => $species,
            'form' => $form->createView(),
        ));
    }
}
