<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\Type\ContactUsReplyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactUsAdminController extends Controller
{
    /**
     * @Route("/admin/contact/list/{page}", name="contact_us_admin_index", defaults={"page": 1}, requirements={"page": "\d*"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction($page)
    {
        if (0 === $page) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        $listMessages = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:ContactUs')
            ->getMessages($page);

        $nbPages = ceil(count($listMessages) / ContactUs::NUM_ITEMS);

        if ($page > $nbPages && $page != 1) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        return $this->render('contactus\index.html.twig', [
            'listMessages' => $listMessages,
            'nbPages' => $nbPages,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/admin/contact/delete/{id}", name="contact_us_delete", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(ContactUs $message, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'The message has been deleted.');

            return $this->redirectToRoute('contact_us_admin_index');
        }

        return $this->render('contactus\delete.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/contact/reply/{id}", name="contact_us_reply", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function replyAction(ContactUs $message, Request $request)
    {
        $answer = "Dear {$message->getFirstName()} {$message->getLastName()}, \n";
        $answer .= "First of all, we want to thank you for your interest in Gryc.\n\n\n";
        $answer .= "Best Regards,\n";
        $answer .= "The GRYC team\n\n";
        $answer .= "Postscript: If you have any questions about this issue, you can contact me at this mail: {$this->getUser()->getEmail()}";

        $form = $this->createForm(ContactUsReplyType::class, $data = ['answer' => $answer]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->get('app.mailer')->sendReplyContactEmailMessage($message, $form->getData())) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($message);
                $em->flush();

                $this->addFlash('success', 'Your message has been send.');

                return $this->redirectToRoute('contact_us_admin_index');
            } else {
                $this->addFlash('warning', 'An error occured.');

                return $this->redirectToRoute('contact_us_reply', [
                    'id' => $message->getId(),
                ]);
            }
        }

        return $this->render('contactus\reply.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }

    public function numberMessageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbMessages = $em->getRepository('AppBundle:ContactUs')->getNumberMessages();

        return $this->render('contactus\numberMessages.html.twig', [
            'nbMessages' => $nbMessages,
        ]);
    }
}
