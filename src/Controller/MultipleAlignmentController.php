<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use App\Entity\MultipleAlignment;
use App\Form\Type\MultipleAlignmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MultipleAlignmentController extends Controller
{
    /**
     * @Route("/tool/multiple-alignment", methods={"GET", "POST"}, name="multiple_alignment_index")
     * @Route("/tool/multiple-alignment/{name}", methods={"GET", "POST"}, name="multiple_alignment_index_prefilled")
     */
    public function indexAction(MultipleAlignment $multipleAlignment = null, Request $request)
    {
        $multipleAlignmentManager = $this->get('App\Service\MultipleAlignmentManager');
        $multipleAlignment = $multipleAlignmentManager->initAlignment($multipleAlignment);

        $form = $this->createForm(MultipleAlignmentType::class, $multipleAlignment);

        // Get previous user multiple alignments
        $em = $this->getDoctrine()->getManager();
        if (null !== $this->getUser()) {
            $previousMultipleAlignments = $em->getRepository('App:MultipleAlignment')->findBy(['createdBy' => $this->getUser()], ['created' => 'DESC'], MultipleAlignment::NB_KEPT_ALIGNMENT);
        } else {
            $previousMultipleAlignments = null;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $multipleAlignmentManager->save($multipleAlignment);

            return $this->redirectToRoute('multiple_alignment_view', [
                'name' => $multipleAlignment->getName(),
            ]);
        }

        return $this->render('tools/multiple_alignment/index.html.twig', [
            'form' => $form->createView(),
            'previousMultipleAlignments' => $previousMultipleAlignments,
        ]);
    }

    /**
     * @Route("/tool/multiple-alignment/view/{name}", methods={"GET"}, name="multiple_alignment_view")
     */
    public function viewAction(MultipleAlignment $multipleAlignment, Request $request)
    {
        if ('finished' === $multipleAlignment->getStatus()) {
            $alignment = $this->get('App\Service\AlignmentManipulator')->getGlobalAlignment($multipleAlignment->getOutput(), $request->query->get('coloration'), $request->query->get('level'));
        } else {
            $alignment = null;
        }

        return $this->render('tools/multiple_alignment/view.html.twig', [
            'multipleAlignment' => $multipleAlignment,
            'alignment' => $alignment,
        ]);
    }
}
