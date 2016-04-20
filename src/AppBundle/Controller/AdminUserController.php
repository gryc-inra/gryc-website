<?php
// src/AppBundle/Controller/AdminUserController.php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\AdminUserRightsType;
use AppBundle\Form\Type\AdminUserRolesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin user management controller.
 *
 * @Route("admin/user")
 */
class AdminUserController extends Controller
{
    /**
     * List all users.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/list", name="user_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('admin/user/user-list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Edit user rights.
     *
     * @param Request $request
     * @param User    $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit-rights/{usernameCanonical}", name="user_rights_edit")
     */
    public function editRightsAction(Request $request, User $user)
    {
        $species = $this->getDoctrine()->getManager()->getRepository('AppBundle:Species')->getAllSpeciesWithAvailableStrains($this->getUser());

        $form = $this->createForm(AdminUserRightsType::class, $user);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The user rights were edited !');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/edit-rights.html.twig', array(
            'user' => $user,
            'speciesList' => $species,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edit user roles.
     *
     * @param Request $request
     * @param User    $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit-roles/{usernameCanonical}", name="user_roles_edit")
     */
    public function editRolesAction(Request $request, User $user)
    {
        $form = $this->createForm(AdminUserRolesType::class, $user);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The user roles were edited !');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/edit-roles.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
