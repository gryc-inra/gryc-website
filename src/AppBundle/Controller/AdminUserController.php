<?php
// src/AppBundle/Controller/AdminUserController.php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\AdminUserRightsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("admin/user")
 */
class AdminUserController extends Controller
{
    /**
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
     * @Route("/edit-rights/{usernameCanonical}", name="user_rights_edit")
     */
    public function editRightsAction(Request $request, User $user)
    {
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
            'form' => $form->createView(),
        ));
    }
}
