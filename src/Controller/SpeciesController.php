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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Species controller.
 */
class SpeciesController extends Controller
{
    /**
     * @Route("/db/{species_slug}", methods={"GET"}, name="species_view")
     * @Route("/species/{species_slug}")
     */
    public function viewAction($species_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('App:Species')->getSpeciesAndAvailableStrains($species_slug, $this->getUser());

        if (null === $species) {
            throw $this->createNotFoundException();
        }

        return $this->render('species/view.html.twig', [
            'species' => $species,
        ]);
    }
}
