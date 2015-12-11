<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\ContactUsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ContactUsController
 * @package AppBundle\Controller
 * @Route("/contact")
 */
class ContactUsController extends Controller
{
    /**
     * @Route("/", name="contact_us")
     */
    public function contactAction(Request $request)
    {
        $contactus = new ContactUs();

        if ($user = $this->getUser()) {
            $contactus->setFirstName($user->getFirstName());
            $contactus->setLastName($user->getLastName());
            $contactus->setEmail($user->getEmailCanonical());
        }


        $form = $this->createForm(ContactUsType::class, $contactus);
        $form->add('save', SubmitType::class, array(
            'label' => 'Send message',
            'attr' => array(
                'class' => 'btn btn-default'
            )
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.mailer')->sendConfirmationContactEmailMessage($contactus);

            $em = $this->getDoctrine()->getManager();
            $em->persist($contactus);
            $em->flush();

            $this->addFlash('success', 'Your message has been submitted.');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('contactus\contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/list/{page}", name="contact_us_homepage", defaults={"page": 1}, requirements={"page": "\d*"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction($page)
    {
        if (0 === $page) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        $nbPerPage = 10;

        $listMessages = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:ContactUs')
            ->getMessages($page, $nbPerPage);

        $nbPages = ceil(count($listMessages)/$nbPerPage);

        if ($page > $nbPages && $page != 1) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        return $this->render('contactus\index.html.twig', array(
            'listMessages' => $listMessages,
            'nbPages' => $nbPages,
            'page' => $page
        ));
    }

    /**
     * @Route("/delete/{id}", name="contact_us_delete", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(ContactUs $message, Request $request)
    {
        // Créer un formulaire vide (jeton CSRF), pour demander confirmation de la suppression
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'The message has been deleted.');

            return $this->redirectToRoute('contact_us_homepage');
        }

        return $this->render('contactus\delete.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reply/{id}", name="contact_us_reply", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function replyAction(ContactUs $message, Request $request)
    {
        // Ici on va créer le formulaire directement, inutile de passer par une entité, car on ne garde pas les réponses
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array(
                'data' => $this->getUser()->getFirstName().' '.$this->getUser()->getLastName(),
            ))
            ->add('email', EmailType::class, array(
                'data' => $this->getUser()->getEmailCanonical(),
            ))
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'rows' => 20,
                ),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 30)),
                ),
            ))
            ->add('reply', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // data is an array with "name", "email" and "message" keys
            $data = $form->getData();

            $this->get('app.mailer')->sendReplyContactEmailMessage($message, $data);

            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'Your message has been send.');

            return $this->redirectToRoute('contact_us_homepage');
        }

        return $this->render('contactus\reply.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }

    public function numberMessageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $nbMessages = $em->getRepository('AppBundle:ContactUs')->getNumberMessages();

        return $this->render('contactus\numberMessages.html.twig', array(
            'nbMessages' => $nbMessages,
        ));
    }
}
