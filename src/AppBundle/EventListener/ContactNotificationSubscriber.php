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

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Templating\EngineInterface;

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
     * @var EngineInterface
     */
    private $templating;

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
     * @param \Swift_Mailer   $mailer
     * @param EngineInterface $templating
     * @param string          $sender
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, $senderMail, $senderName)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
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
        $body = $this->templating->render('mail/contactMessage.html.twig', [
            'data' => $data,
        ]);

        $message = \Swift_Message::newInstance()
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($this->senderMail, $this->senderName)
            ->setReplyTo($data['email'], $data['firstName'].' '.$data['lastName'])
            ->setSubject($subject)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }

    private function notifyUser($data)
    {
        $subject = 'Reception of your message';
        $body = $this->templating->render('mail/confirmationContactMessage.html.twig', [
        'data' => $data, ]
        );

        $message = \Swift_Message::newInstance()
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($data['email'], $data['firstName'].' '.$data['lastName'])
            ->setSubject($subject)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }
}
