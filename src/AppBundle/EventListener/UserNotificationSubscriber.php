<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Templating\EngineInterface;

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
            Events::USER_REGISTERED => 'onUserRegistered',
            Events::USER_RESET => 'onUserReset',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onUserRegistered(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();

        $subject = 'Registration confirmation';
        $body = $this->templating->render('mail/userConfirmation.html.twig', [
            'user' => $user,
        ]);

        $message = \Swift_Message::newInstance()
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }

    /**
     * @param GenericEvent $event
     */
    public function onUserReset(GenericEvent $event)
    {
        /** @var User $user */
        $user = $event->getSubject();

        $subject = 'Password resetting';
        $body = $this->templating->render('mail/passwordResetting.html.twig', [
            'user' => $user,
        ]);

        $message = \Swift_Message::newInstance()
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }
}
