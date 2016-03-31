<?php
// src/AppBundle/Utils/Mailer.php

namespace AppBundle\Utils;

use AppBundle\Entity\ContactUs;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Service Mailer, permettant d'envoyer les mails.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 *
 * @Route("/contact")
 */
class Mailer
{
    protected $mailer;
    protected $templating;
    private $from = 'no-reply@gryc.dev';
    private $name = 'GRYC - The yeast genomics database';

    public function __construct($mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Fonction principale d'envois de mail, défini les attributs de SwiftMailer.
     *
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
    /**
     * Envoi un mail de confirmation de réception d'un message à un visiteur.
     *
     * @param \ContactUs $contactMessage
     */
    public function sendConfirmationContactEmailMessage(ContactUs $contactMessage)
    {
        $to = $contactMessage->getEmail();
        $from = array($this->from => $this->name);
        $subject = 'Reception of your message';
        $body = $this->templating->render('mail/confirmationContactMessage.html.twig', array('contactMessage' => $contactMessage));

        $this->sendEmailMessage($to, $from, $subject, $body);
    }
    /**
     * Envoi d'un mail de réponse à une question posée par un visiteur.
     *
     * @param \ContactUs question
     * @param array reply
     * @param string fromName
     * @param string fromMail
     */
    public function sendReplyContactEmailMessage(ContactUs $question, $reply, $fromName, $fromMail)
    {
        $to = $question->getEmail();
        $from = array($fromMail => $fromName);
        $subject = 'Reply about your message';
        $body = $this->templating->render('mail/replyContactMessage.html.twig', array('question' => $question, 'reply' => $reply));

        $this->sendEmailMessage($to, $from, $subject, $body);
    }
}
