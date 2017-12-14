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
            $query = $form->getData()['query'];
            $action = $form->getData()['action'];
            $result = $this->get('AppBundle\Service\SequenceManipulator')->processManipulation($query, $action);

            return $this->render('tools/reverse_complement/result.html.twig', [
                'query' => $form->getData()['query'],
                'action' => $form->getData()['action'],
                'result' => $result,
            ]);
        }

        return $this->render('tools/reverse_complement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
