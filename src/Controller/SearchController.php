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

use App\Form\Type\AdvancedSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Search engine controller.
 */
class SearchController extends Controller
{
    /**
     * @Route("/search", name="quick-search")
     */
    public function quickSearchAction(Request $request)
    {
        $keyword = null !== $request->get('q') ? $request->get('q') : '';
        $keyword = mb_convert_encoding($keyword, 'UTF-8');

        $repositoryManager = $this->get('fos_elastica.manager');
        $repository = $repositoryManager->getRepository('App:Locus');
        $results = $repository->findByNameNoteAnnotation($keyword, $this->getUser());

        // Return the view
        return $this->render('search/quickSearch.html.twig', [
            'search' => $keyword,
            'results' => $results,
        ]);
    }

    /**
     * @Route("/advanced-search", name="advanced-search")
     */
    public function advancedSearchAction(Request $request)
    {
        $data = null !== $request->get('q') ? ['search' => $request->get('q')] : null;
        $form = $this->createForm(AdvancedSearchType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $repositoryManager = $this->get('fos_elastica.manager');
            $repository = $repositoryManager->getRepository('App:Locus');
            $results = $repository->findByNameNoteAnnotation($data['search'], $this->getUser(), $data['strains']);

            // Return the view
            return $this->render('search/advancedSearch.html.twig', [
                'form' => $form->createView(),
                'search' => $data['search'],
                'results' => $results,
            ]);
        }

        // Return the view
        return $this->render('search/advancedSearch.html.twig', [
            'form' => $form->createView(),
            'search' => $data['search'],
        ]);
    }
}
