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

use App\Entity\Blast;
use App\Form\Type\BlastType;
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
        $blastManager = $this->get('App\Service\BlastManager');
        $blast = $blastManager->initBlast($blast);

        $form = $this->createForm(BlastType::class, $blast);

        // Get previous user blasts
        $em = $this->getDoctrine()->getManager();
        if (null !== $this->getUser()) {
            $previousBlasts = $em->getRepository('App:Blast')->findBy(['createdBy' => $this->getUser()], ['created' => 'DESC'], Blast::NB_KEPT_BLAST);
        } else {
            $previousBlasts = null;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blastManager->save($blast);

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
            $result = $this->get('App\Service\BlastManager')->xmlToArray($blast);
        } else {
            $result = null;
        }

        return $this->render('tools/blast/view.html.twig', [
            'blast' => $blast,
            'result' => $result,
        ]);
    }
}
