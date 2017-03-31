<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ResetPasswordType;
use AppBundle\Form\Type\ResettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ResettingController extends Controller
{
    /**
     * @Route("/resetting/request", name="user_resetting_request")
     */
    public function resettingRequestAction(Request $request)
    {
        $form = $this->createForm(ResettingType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            // Get user
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->findOneByEmail($email);

            if (null === $user) {
                $this->addFlash('warning', 'There is no user with this email address.');

                return $this->redirectToRoute('user_resetting_request');
            }

            if (!$user->isEnabled()) {
                $this->addFlash('warning', 'Your account is not activated.');

                return $this->redirectToRoute('login');
            }

            // Generate a token, to activate account
            $tokenGenerator = $this->get('app.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $em->flush();

            // Send an email with the reset link
            $this->get('app.mailer')->sendPasswordResetting($user);

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'user/resetting/resetting_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/resetting/{username}/{token}", name="user_resetting")
     */
    public function resettingAction($username, $token, Request $request)
    {
        $username = rawurldecode($username);
        $token = rawurldecode($token);

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByEmail($username);

        if (!$user->isEnabled()) {
            $this->addFlash('warning', 'Your account is not activated.');

            return $this->redirectToRoute('login');
        }

        if (null === $user->getConfirmationToken() ||
            !hash_equals($user->getConfirmationToken(), $token)
        ) {
            $this->addFlash('warning', 'The confirmation token is not valid.');

            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            // Delete the token
            $user->setConfirmationToken(null);
            $em->flush();

            $this->addFlash('success', 'Your password have been successfully changed. You can now log in with this new one.');

            return $this->redirectToRoute('login');
        }

        return $this->render('user/resetting/resetting.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
