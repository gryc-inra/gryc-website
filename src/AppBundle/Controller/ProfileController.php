<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ChangePasswordType;
use AppBundle\Form\Type\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/my-profile", name="user_profile")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function profileAction()
    {
        $user = $this->getUser();

        return $this->render('user/profile/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/my-profile/edit", name="user_profile_edit")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function profileEditAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('app.user_manager');
            $userManager->updateUser($user);

            $this->addFlash('success', 'Your profile have been successfully edited.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/my-profile/password/edit", name="user_password_edit")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editPasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('app.user_manager');
            $userManager->updateUser($user);

            $this->addFlash('success', 'Your password have been successfully changed.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/password/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
