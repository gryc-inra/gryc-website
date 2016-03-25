<?php
// src/AppBundle/Controller/AdminUserController.php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\AdminUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
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

        return $this->render('admin/user/list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Route("/edit/{usernameCanonical}", name="user_edit")
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->add('Editer', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The user was edited !');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
