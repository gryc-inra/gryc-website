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

use App\Entity\User;
use App\Form\Type\UserRightsType;
use App\Form\Type\UserRolesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserAdminController.
 *
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserAdminController extends Controller
{
    /**
     * @Route("/admin/users", methods={"GET"}, options={"expose"=true}, name="user_index")
     */
    public function indexAction(Request $request)
    {
        $list = $this->listAction($request);

        return $this->render('user/admin/index.html.twig', [
            'list' => $list,
            'query' => $request->get('q'),
        ]);
    }

    /**
     * @Route(
     *     "/admin/users/list",
     *     options={"expose"=true},
     *     condition="request.isXmlHttpRequest()",
     *     methods={"GET"},
     *     name="user_index_ajax"
     * )
     */
    public function listAction(Request $request)
    {
        $query = ('' !== $request->get('q') && null !== $request->get('q')) ? $request->get('q') : null;
        $page = (0 < (int) $request->get('p')) ? $request->get('p') : 1;

        $repositoryManager = $this->get('fos_elastica.manager.orm');
        $repository = $repositoryManager->getRepository('App:User');
        $elasticQuery = $repository->searchByNameQuery($query, $page, $this->getUser());
        $usersList = $this->get('fos_elastica.finder.app.user')->find($elasticQuery);
        $nbResults = $this->get('fos_elastica.index.app.user')->count($elasticQuery);

        $nbPages = ceil($nbResults / User::NUM_ITEMS);

        return $this->render('user/admin/_list.html.twig', [
            'usersList' => $usersList,
            'query' => $query,
            'page' => $page,
            'nbPages' => $nbPages,
        ]);
    }

    /**
     * @Route("/admin/user/roles/{id}", methods={"GET", "POST"}, name="user_roles")
     */
    public function rolesAction(User $user, Request $request)
    {
        $form = $this->createForm(UserRolesType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The user\'s roles have been successfully edited.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/admin/roles.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/strain-access-rights/{id}", methods={"GET", "POST"}, name="user_strains")
     */
    public function strainAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserRightsType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'The user rights were edited !');

            return $this->redirectToRoute('user_index');
        }

        $species = $em->getRepository('App:Species')->getAllSpeciesAndStrains();

        return $this->render('user/admin/strain_access.twig', [
            'user' => $user,
            'speciesList' => $species,
            'form' => $form->createView(),
        ]);
    }
}
