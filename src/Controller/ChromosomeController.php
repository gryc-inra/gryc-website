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

use App\Entity\Chromosome;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChromosomeController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}/{chromosome_slug}", methods={"GET"}, name="chromosome_view")
     * @Route("/chromosome/{chromosome_slug}", methods={"GET"})
     * @Entity("chromosome", expr="repository.getChromosomeWithStrainAndSpecies(chromosome_slug)")
     * @Security("is_granted('VIEW', chromosome.getStrain())")
     */
    public function viewAction(Chromosome $chromosome)
    {
        return $this->render('chromosome/view.html.twig', [
           'chromosome' => $chromosome,
        ]);
    }
}
