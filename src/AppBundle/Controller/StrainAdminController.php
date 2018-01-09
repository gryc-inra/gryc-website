<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
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

use AppBundle\Entity\Strain;
use AppBundle\Form\Type\StrainRightsType;
use AppBundle\Form\Type\StrainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Strain administration controller.
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class StrainAdminController extends Controller
{
    /**
     * @Route("/admin/strain", name="strain_admin_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $strains = $em->getRepository('AppBundle:Strain')->getStrainsWithSpeciesAndClade();

        return $this->render('strain/admin/index.html.twig', [
            'strains' => $strains,
        ]);
    }

    /**
     * @Route("/admin/strain/{id}/edit", name="strain_edit")
     */
    public function editAction(Request $request, Strain $strain)
    {
        $form = $this->createForm(StrainType::class, $strain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The strain was successfully edited.');

            return $this->redirectToRoute('strain_admin_index');
        }

        return $this->render('strain/admin/edit.html.twig', [
            'strain' => $strain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/admin/strain/{id}/user-rights", name="strain_user_rights")
     */
    public function userRightsAction(Request $request, Strain $strain)
    {
        if ($strain->isPublic()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(StrainRightsType::class, $strain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The user\'s rights for the strain '.$strain->getName().' were successfully edited.');

            return $this->redirectToRoute('strain_admin_index');
        }

        return $this->render('strain/admin/userRights.html.twig', [
            'strain' => $strain,
            'form' => $form->createView(),
        ]);
    }
}
