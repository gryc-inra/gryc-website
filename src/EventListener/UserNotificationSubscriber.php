<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\EventListener;

use App\Entity\User;
use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Notifies user.
 */
class UserNotificationSubscriber implements EventSubscriberInterface
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
     * @param string $senderMail
     * @param string $senderName
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, $senderMail, $senderName)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->senderMail = $senderMail;
        $this->senderName = $senderName;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::USER_REGISTERED => 'onUserRegistered',
            Events::USER_RESET => 'onUserReset',
        ];
    }

    public function onUserRegistered(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();

        $subject = 'Registration confirmation';

        try {
            $textBody = $this->templating->render('mail/user_confirmation.txt.twig', [
                'user' => $user,
            ]);
            $htmlBody = $this->templating->render('mail/user_confirmation.html.twig', [
                'user' => $user,
            ]);
        } catch (\Exception $exception) {
            return new \Error('Template generation failed');
        }

        $message = (new \Swift_Message())
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody($textBody, 'text/plain')
            ->addPart($htmlBody, 'text/html')
        ;

        $this->mailer->send($message);
    }

    public function onUserReset(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();

        $subject = 'Password resetting';

        try {
            $textBody = $this->templating->render('mail/password_resetting.txt.twig', [
                'user' => $user,
            ]);
            $htmlBody = $this->templating->render('mail/password_resetting.html.twig', [
                'user' => $user,
            ]);
        } catch (\Exception $exception) {
            return new \Error('Template generation failed');
        }

        $message = (new \Swift_Message())
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody($textBody, 'text/plain')
            ->addPart($htmlBody, 'text/html')
        ;

        $this->mailer->send($message);
    }
}
