<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\Type\ContactUsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactUsController extends Controller
{
    /**
     * @Route("/contact", name="contact_us")
     */
    public function contactAction(Request $request)
    {
        $contactus = new ContactUs();
        // Pre fill fields
        if ($user = $this->getUser()) {
            $contactus->setFirstName($user->getFirstName());
            $contactus->setLastName($user->getLastName());
            $contactus->setEmail($user->getEmail());
        }

        $form = $this->createForm(ContactUsType::class, $contactus);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactus);
            $em->flush();

            $this->get('app.mailer')->sendConfirmationContactEmailMessage($contactus);

            $this->addFlash('success', 'Your message has been submitted.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('contactus\contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
