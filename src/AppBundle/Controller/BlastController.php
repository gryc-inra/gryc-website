<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blast;
use AppBundle\Form\Type\BlastType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlastController extends Controller
{
    /**
     * @Route("/blast", name="blast_index")
     * @Route("/blast/{name}", name="blast_index_prefilled")
     */
    public function indexAction(Blast $blast = null, Request $request)
    {
        $blastManager = $this->get('AppBundle\Service\BlastManager');
        $blast = $blastManager->initBlast($blast);

        $form = $this->createForm(BlastType::class, $blast);

        // Get previous user blasts
        $em = $this->getDoctrine()->getManager();
        if (null !== $this->getUser()) {
            $previousBlasts = $em->getRepository('AppBundle:Blast')->findBy(['createdBy' => $this->getUser()], ['created' => 'DESC'], Blast::NB_KEPT_BLAST);
        } else {
            $previousBlasts = null;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($blast);
            $em->flush();

            // Redirect the user on the result page
            return $this->redirectToRoute('blast_view', [
                'name' => $blast->getName(),
            ]);
        }

        return $this->render('tools/blast/index.html.twig', [
            'form' => $form->createView(),
            'previousBlasts' => $previousBlasts,
        ]);
    }

    /**
     * @Route("/blast/view/{name}", name="blast_view")
     */
    public function viewAction(Blast $blast)
    {
        if ('finished' === $blast->getStatus()) {
            $result = $this->get('AppBundle\Service\BlastManager')->xmlToArray($blast);
        } else {
            $result = null;
        }

        return $this->render('tools/blast/view.html.twig', [
            'blast' => $blast,
            'result' => $result,
        ]);
    }
}
