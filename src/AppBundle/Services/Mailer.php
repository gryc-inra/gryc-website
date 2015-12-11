<?php
// src/AppBundle/Services/Mailer.php

namespace AppBundle\Services;

use AppBundle\Entity\ContactUs;
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
    protected function sendEmailMessage($to, $subject, $body)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom(array($this->from => $this->name))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setCharset('utf-8')
            ->setContentType('text/html');

        $this->mailer->send($message);
    }

    public function sendConfirmationContactEmailMessage(ContactUs $contactMessage)
    {
        $to = $contactMessage->getEmail();
        $subject = 'Reception of your message';
        $template = 'Mail/confirmation_contact_message.html.twig';
        $body = $this->templating->render($template, array('contactMessage' => $contactMessage));

        $this->sendEmailMessage($to, $subject, $body);
    }
}