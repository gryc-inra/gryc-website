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

use App\Entity\Species;
use App\Form\Type\SpeciesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Species administration controller.
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SpeciesAdminController extends Controller
{
    /**
     * A constant that contain the api url.
     */
    const NCBI_TAXONOMY_API_LINK = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=taxonomy&id=';

    /**
     * List all the species in the admin section.
     *
     * @Route("/admin/species", name="species_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('App:Species')->getAllSpeciesAndStrains();

        return $this->render('species/admin/index.html.twig', [
            'speciesList' => $species,
        ]);
    }

    /**
     * Add a species.
     *
     * @Route("/admin/species/add", name="species_add")
     */
    public function addAction(Request $request)
    {
        $species = new Species();
        $form = $this->createForm(SpeciesType::class, $species);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($species);
            $em->flush();

            $this->addFlash('success', 'The species was successfully added.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a species.
     *
     * @Route("/admin/species/{id}/edit", name="species_edit")
     */
    public function editAction(Request $request, Species $species)
    {
        $form = $this->createForm(SpeciesType::class, $species);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The species was successfully edited.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/admin/edit.html.twig', [
            'species' => $species,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a species.
     *
     * @Route("/admin/species/{id}/delete", name="species_delete")
     */
    public function deleteAction(Request $request, Species $species)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, [
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($species);
            $em->flush();

            $this->addFlash('success', 'The species was successfully deleted.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('species/admin/delete.html.twig', [
            'species' => $species,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Consult the ncbi taxonomy api, and return a json with the interesting data.
     * Used in AddSpecies for the autocomplete method.
     *
     * @Route("/admin/species/json/{taxid}", name="species_getjson", condition="request.isXmlHttpRequest()")
     */
    public function getJsonAction($taxid)
    {
        return new JsonResponse($this->get('App\Service\TaxId')->getArray($taxid));
    }
}
