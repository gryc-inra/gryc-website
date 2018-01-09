<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

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
