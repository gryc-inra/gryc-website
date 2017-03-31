<?php

// src/AppBundle/Controller/CladeController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Clade;
use AppBundle\Form\Type\CladeEditType;
use AppBundle\Form\Type\CladeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Clade controller.
 *
 * @Route("/admin/clade")
 */
class CladeController extends Controller
{
    /**
     * List all the clades.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/list", name="clades_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clades = $em->getRepository('AppBundle:Clade')->getCladesWithSpecies();

        return $this->render('clade/list.html.twig', [
            'clades' => $clades,
        ]);
    }

    /**
     * Add a clade.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add", name="clade_add")
     */
    public function addAction(Request $request)
    {
        $clade = new Clade();

        $form = $this->createForm(CladeType::class, $clade);
        $form->add('save', SubmitType::class, [
            'label' => 'Add a clade',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clade);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The clade was successfully added.');

            return $this->redirectToRoute('clades_list');
        }

        return $this->render('clade/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a clade.
     *
     * @param Request $request
     * @param Clade   $clade
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}/edit", name="clade_edit")
     */
    public function editAction(Request $request, Clade $clade)
    {
        $form = $this->createForm(CladeEditType::class, $clade);
        $form->add('save', SubmitType::class, [
            'label' => 'Edit the clade',
            'attr' => [
                'class' => 'btn btn-warning',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The clade was successfully edited.');

            return $this->redirectToRoute('clades_list');
        }

        return $this->render('clade/edit.html.twig', [
            'clade' => $clade,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a clade.
     *
     * @param Request $request
     * @param Clade   $clade
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}/delete", name="clade_delete")
     */
    public function deleteAction(Request $request, Clade $clade)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, [
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clade);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The clade was successfully deleted.');

            return $this->redirectToRoute('clades_list');
        }

        return $this->render('clade/delete.html.twig', [
            'clade' => $clade,
            'form' => $form->createView(),
        ]);
    }
}
