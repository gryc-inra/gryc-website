<?php

namespace AppBundle\Controller;

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
        $form = $this->createForm(ContactUsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('AppBundle\Utils\Mailer')->sendContactMessage($form->getData());

            $this->addFlash('success', 'Your message has been submitted.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('contactus\contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
