<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MultipleAlignment;
use AppBundle\Form\Type\MultipleAlignmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MultipleAlignmentController extends Controller
{
    /**
     * @Route("/tool/multiple-alignment", name="multiple_alignment_index")
     * @Route("/tool/multiple-alignment/{name}", name="multiple_alignment_index_prefilled")
     */
    public function indexAction(MultipleAlignment $multipleAlignment = null, Request $request)
    {
        $multipleAlignmentManager = $this->get('app.multiple_alignment_manager');
        if (null === $multipleAlignment && null === $multipleAlignment = $multipleAlignmentManager->getLastAlignment()) {
            $multipleAlignment = new MultipleAlignment();
        } else {
            $multipleAlignment = clone $multipleAlignment;
        }

        $form = $this->createForm(MultipleAlignmentType::class, $multipleAlignment);

        // Get previous user multiple alignments
        $em = $this->getDoctrine()->getManager();
        if (null !== $this->getUser()) {
            $previousMultipleAlignments = $em->getRepository('AppBundle:MultipleAlignment')->findBy(['createdBy' => $this->getUser()], ['created' => 'DESC'], MultipleAlignment::NB_KEPT_ALIGNMENT);
        } else {
            $previousMultipleAlignments = null;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($multipleAlignment);
            $em->flush();

            // Add the Blast in RabbitMq Queue
            $this->get('old_sound_rabbit_mq.multiple_alignment_producer')->publish($multipleAlignment->getId());

            // Add the blast as last blast in user session
            $request->getSession()->set('last_multiple_alignment', $multipleAlignment->getId());

            // Redirect the user on the result page
            return $this->redirectToRoute('multiple_alignment_view', [
                'name' => $multipleAlignment->getName(),
            ]);
        }

        return $this->render('multiple_alignment/index.html.twig', [
            'form' => $form->createView(),
            'previousMultipleAlignments' => $previousMultipleAlignments,
        ]);
    }

    /**
     * @Route("/tool/multiple-alignment/view/{name}", name="multiple_alignment_view")
     */
    public function viewAction(MultipleAlignment $multipleAlignment)
    {
        return $this->render('multiple_alignment/view.html.twig', [
            'alignment' => $multipleAlignment,
        ]);
    }
}
