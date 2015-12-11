<?php
// src/AppBundle/Services/Mailer.php

namespace AppBundle\Services;

use AppBundle\Entity\ContactUs;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Mailer
{
    protected $mailer;
    protected $templating;
    private $from = "no-reply@gryc.dev";
    private $name = "GRYC - The yeast genomics database";

    public function __construct($mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     */
    protected function sendEmailMessage($to, $from, $subject, $body)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom($from)
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->setBody($body)
            ->setCharset('utf-8')
            ->setContentType('text/html');

        $this->mailer->send($message);
    }

    public function sendConfirmationContactEmailMessage(ContactUs $contactMessage)
    {
        $to = $contactMessage->getEmail();
        $from = array($this->from => $this->name);
        $subject = 'Reception of your message';
        $body = $this->templating->render('Mail/confirmation_contact_message.html.twig', array('contactMessage' => $contactMessage));

        $this->sendEmailMessage($to, $from, $subject, $body);
    }

    public function sendReplyContactEmailMessage(ContactUs $answer, $reply)
    {
        $to = $answer->getEmail();
        $from = array($reply['email'] => $reply['name']);
        $subject = 'Reply about your message';
        $body = $this->templating->render('Mail/reply_contact_message.html.twig', array('answer' => $answer, 'reply' => $reply));

        $this->sendEmailMessage($to, $from, $subject, $body);
    }
}