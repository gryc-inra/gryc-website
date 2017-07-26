<?php

namespace AppBundle\Controller;

use AppBundle\Events;
use AppBundle\Form\Type\ContactUsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class ContactUsController extends Controller
{
    /**
     * @Route("/contact", name="contact_us")
     */
    public function contactAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $form = $this->createForm(ContactUsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event = new GenericEvent($form->getData());
            $eventDispatcher->dispatch(Events::CONTACT_MESSAGE, $event);

            $this->addFlash('success', 'Your message has been submitted.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('contactus\contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
