<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Clade;
use AppBundle\Form\Type\CladeEditType;
use AppBundle\Form\Type\CladeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Clade controller.
 */
class CladeController extends Controller
{
    /**
     * @Route("/admin/clade", name="clade_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clades = $em->getRepository('AppBundle:Clade')->getCladesWithSpecies();

        return $this->render('clade/index.html.twig', [
            'clades' => $clades,
        ]);
    }

    /**
     * @Route("/admin/clade/add", name="clade_add")
     */
    public function addAction(Request $request)
    {
        $clade = new Clade();

        $form = $this->createForm(CladeType::class, $clade);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clade);
            $em->flush();

            $this->addFlash('success', 'The clade has been successfully added.');

            return $this->redirectToRoute('clade_index');
        }

        return $this->render('clade/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/clade/{id}/edit", name="clade_edit")
     */
    public function editAction(Request $request, Clade $clade)
    {
        $form = $this->createForm(CladeEditType::class, $clade);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The clade has been successfully edited.');

            return $this->redirectToRoute('clade_index');
        }

        return $this->render('clade/edit.html.twig', [
            'clade' => $clade,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/clade/{id}/delete", name="clade_delete")
     */
    public function deleteAction(Request $request, Clade $clade)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, [
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clade);
            $em->flush();

            $this->addFlash('success', 'The clade has been successfully deleted.');

            return $this->redirectToRoute('clade_index');
        }

        return $this->render('clade/delete.html.twig', [
            'clade' => $clade,
            'form' => $form->createView(),
        ]);
    }
}
