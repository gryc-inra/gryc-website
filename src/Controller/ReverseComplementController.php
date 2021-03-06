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

use App\Form\Type\ReverseComplementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReverseComplementController extends Controller
{
    /**
     * @Route("/tool/reverse-complement", methods={"GET", "POST"}, name="reverse_complement")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ReverseComplementType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->getData()['query'];
            $action = $form->getData()['action'];
            $result = $this->get('App\Service\SequenceManipulator')->processManipulation($query, $action);

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
