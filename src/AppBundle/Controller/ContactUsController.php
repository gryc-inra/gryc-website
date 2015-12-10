<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\ContactUsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ContactUsController extends Controller
{
    /**
     * @Route("/contact", name="contact")
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
            $em = $this->getDoctrine()->getManager();

            $em->persist($contactus);
            $em->flush();

            $this->addFlash('success', 'Your message has been submitted.');
            $this->get('app.mailer')->sendConfirmationContactEmailMessage($contactus);

            return $this->redirectToRoute('homepage');
        }
        return $this->render('contactus\contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
