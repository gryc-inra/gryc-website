<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
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

namespace App\EventListener;

use App\Events;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Notifies user.
 */
class ContactNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $senderMail;

    /**
     * @var string
     */
    private $senderName;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $templating
     * @param string            $senderMail
     * @param string            $senderName
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, EntityManagerInterface $em, $senderMail, $senderName)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->em = $em;
        $this->senderMail = $senderMail;
        $this->senderName = $senderName;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::CONTACT_MESSAGE => 'onContactMessage',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onContactMessage(GenericEvent $event)
    {
        /** @var array $data */
        $data = $event->getSubject();

        $this->sendToTeam($data);
        $this->notifyUser($data);
    }

    private function sendToTeam($data)
    {
        $subject = '[GRYC Contact]['.$data['category'].'] '.$data['subject'];

        try {
            $textBody = $this->templating->render('mail/contact_message.txt.twig', [
                'data' => $data,
            ]);
            $htmlBody = $this->templating->render('mail/contact_message.html.twig', [
                'data' => $data,
            ]);
        } catch (\Exception $exception) {
            return new \Error('Template generation failed');
        }

        // Retrieve admins from DB
        $admins = $this->em->getRepository('App:User')->getAdmins();
        $adminsMails = [];
        foreach ($admins as $admin) {
            $adminsMails[$admin->getEmail()] = $admin->getFullName();
        }

        if (empty($adminsMails)) {
            return new \Error('No admin mails');
        }

        $message = (new \Swift_Message())
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($adminsMails)
            ->setReplyTo($data['email'], $data['firstName'].' '.$data['lastName'])
            ->setSubject($subject)
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody($textBody, 'text/plain')
            ->addPart($htmlBody, 'text/html')
        ;

        $this->mailer->send($message);
    }

    private function notifyUser($data)
    {
        try {
            $subject = 'Reception of your message';
            $textBody = $this->templating->render('mail/confirmation_contact_message.txt.twig', [
                    'data' => $data, ]
            );
            $htmlBody = $this->templating->render('mail/confirmation_contact_message.html.twig', [
            'data' => $data, ]
            );
        } catch (\Exception $exception) {
            return new \Error('Template generation failed');
        }

        $message = (new \Swift_Message())
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($data['email'], $data['firstName'].' '.$data['lastName'])
            ->setSubject($subject)
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody($textBody, 'text/plain')
            ->addPart($htmlBody, 'text/html')
        ;

        $this->mailer->send($message);
    }
}
