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
            ->setSubject($subject)
            ->setTo($this->senderMail, $this->senderName)
            ->setFrom($data['email'], $data['firstName'].' '.$data['lastName'])
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
        ->setSubject($subject)
        ->setTo($data['email'], $data['firstName'].' '.$data['lastName'])
        ->setFrom($this->senderMail, $this->senderName)
        ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }
}
