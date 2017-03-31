<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\AdminUserRightsType;
use AppBundle\Form\Type\RoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class UserAdminController extends Controller
{
    /**
     * @Route("/admin/users", options={"expose"=true}, name="user_index")
     * @Security("is_granted('ROLE_ADMIN')")
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
     *     name="user_index_ajax"
     * )
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listAction(Request $request)
    {
        $query = ('' !== $request->get('q') && null !== $request->get('q')) ? $request->get('q') : null;
        $page = (0 < (int) $request->get('p')) ? $request->get('p') : 1;

        $repositoryManager = $this->get('fos_elastica.manager.orm');
        $repository = $repositoryManager->getRepository('AppBundle:User');
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
     * @Route("/admii/user/roles/{id}", name="user_roles")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function rolesAction(User $user, Request $request)
    {
        $form = $this->createForm(RoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The user\'s roles have been successfully edited.');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/admin/roles.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/strain-access-rights/{id}", name="user_strains")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function strainAction(Request $request, User $user)
    {
        $species = $this->getDoctrine()->getManager()->getRepository('AppBundle:Species')->getAllSpeciesWithAvailableStrains($this->getUser());

        $form = $this->createForm(AdminUserRightsType::class, $user);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The user rights were edited !');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/admin/strain_access.twig', [
            'user' => $user,
            'speciesList' => $species,
            'form' => $form->createView(),
        ]);
    }
}
