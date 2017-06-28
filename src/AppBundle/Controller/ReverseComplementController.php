<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ReverseComplementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReverseComplementController extends Controller
{
    /**
     * @Route("/tool/reverse-complement", name="reverse_complement")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ReverseComplementType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Transform the fasta in an array
            $sequences = $this->get('app.sequence_manipulator')->fastaToSequencesArray($form->getData()['query']);

            // For each sequence proceed to the selected action
            foreach ($sequences as &$sequence) {
                switch ($form->getData()['action']) {
                    case 'reverse-complement':
                        $sequence['sequence'] = $this->get('app.sequence_manipulator')->reverseComplement($sequence['sequence']);
                        break;

                    case 'reverse':
                        $sequence['sequence'] = $this->get('app.sequence_manipulator')->reverse($sequence['sequence']);
                        break;

                    case 'complement':
                        $sequence['sequence'] = $this->get('app.sequence_manipulator')->complement($sequence['sequence']);
                        break;
                }
            }

            return $this->render('reverse_complement/result.html.twig', [
                'query' => $form->getData()['query'],
                'action' => $form->getData()['action'],
                'sequences' => $sequences,
            ]);
        }

        return $this->render('reverse_complement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
