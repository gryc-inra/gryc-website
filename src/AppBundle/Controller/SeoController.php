<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use AppBundle\Entity\Strain;
use AppBundle\Form\Type\SpeciesSeoType;
use AppBundle\Form\Type\StrainSeoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Seo controller.
 *
 * @Route("/admin/seo")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SeoController extends Controller
{
    /**
     * @Route("/", name="seo_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('AppBundle:Species')->findAllWithSeo();

        return $this->render('seo/index.html.twig', [
            'speciesList' => $species,
        ]);
    }

    /**
     * @Route("/species/{slug}", name="seo_species")
     */
    public function speciesAction(Species $species, Request $request)
    {
        $form = $this->createForm(SpeciesSeoType::class, $species);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The SEOs were edited !');

            return $this->redirectToRoute('seo_homepage');
        }

        return $this->render('seo/species.html.twig', [
            'form' => $form->createView(),
            'species' => $species,
        ]);
    }

    /**
     * @Route("/strain/{slug}", name="seo_strain")
     */
    public function strainAction(Strain $strain, Request $request)
    {
        $form = $this->createForm(StrainSeoType::class, $strain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The SEOs were edited !');

            return $this->redirectToRoute('seo_homepage');
        }

        return $this->render('seo/strain.html.twig', [
            'form' => $form->createView(),
            'strain' => $strain,
        ]);
    }
}
