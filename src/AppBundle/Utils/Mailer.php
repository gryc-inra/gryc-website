<?php

// src/AppBundle/Utils/Mailer.php

namespace AppBundle\Utils;

use AppBundle\Entity\ContactUs;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Service Mailer, permettant d'envoyer les mails.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 *
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
     * Send an email to confirm a user registration.
     *
     * @param User $user
     */
    public function sendUserConfirmation(User $user)
    {
        $from = [$this->from => $this->name];
        $to = $user->getEmail();
        $subject = 'Registration confirmation';
        $body = $this->templating->render('mail/userConfirmation.html.twig', [
            'user' => $user,
        ]);

        $this->sendEmailMessage($from, $to, $subject, $body);
    }

    /**
     * Send an email to reset the password.
     *
     * @param User $user
     */
    public function sendPasswordResetting(User $user)
    {
        $from = [$this->from => $this->name];
        $to = $user->getEmail();
        $subject = 'Password resetting';
        $body = $this->templating->render('mail/passwordResetting.html.twig', [
            'user' => $user,
        ]);

        $this->sendEmailMessage($from, $to, $subject, $body);
    }

    /**
     * Envoi un mail de confirmation de réception d'un message à un visiteur.
     *
     * @param ContactUs $contactMessage
     */
    public function sendConfirmationContactEmailMessage(ContactUs $contactMessage)
    {
        $to = $contactMessage->getEmail();
        $from = [$this->from => $this->name];
        $subject = 'Reception of your message';
        $body = $this->templating->render('mail/confirmationContactMessage.html.twig', ['contactMessage' => $contactMessage]);

        $this->sendEmailMessage($to, $from, $subject, $body);
    }

    /**
     * Envoi d'un mail de réponse à une question posée par un visiteur.
     *
     * @param ContactUs $question
     * @param array $reply
     * @param string $fromName
     * @param string f$romMail
     */
    public function sendReplyContactEmailMessage(ContactUs $question, $reply, $fromName, $fromMail)
    {
        $to = $question->getEmail();
        $from = [$fromMail => $fromName];
        $subject = 'Reply about your message';
        $body = $this->templating->render('mail/replyContactMessage.html.twig', ['question' => $question, 'reply' => $reply]);

        $this->sendEmailMessage($to, $from, $subject, $body);
    }
}
