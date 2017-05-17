<?php

// src/AppBundle/Utils/Mailer.php

namespace AppBundle\Utils;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Service Mailer, permettant d'envoyer les mails.
 *
 * @author Mathieu Piot (mathieu.piot[at]agroparistech.fr)
 */
class Mailer
{
    protected $mailer;
    protected $templating;
    private $from = 'gryc.inra@gmail.com';
    private $name = 'GRYC - The yeast genomics database';

    public function __construct($mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Fonction principale d'envois de mail, défini les attributs de SwiftMailer.
     *
     * @param array        $from
     * @param array|string $to
     * @param string       $subject
     * @param string       $body
     */
    protected function sendEmailMessage($from, $to, $subject, $body)
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

        return $this->mailer->send($message);
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
     * Send the contact message.
     *
     * @param $data
     */
    public function sendContactMessage($data)
    {
        $from = [$data['email'] => $data['firstName'].' '.$data['lastName']];
        $to = [$this->from => $this->name];
        $subject = '[GRYC Contact] '.$data['subject'];
        $body = $this->templating->render('mail/contactMessage.html.twig', ['data' => $data]);

        $this->sendEmailMessage($from, $to, $subject, $body);

        $this->sendConfirmationContactMessage($data);
    }

    /**
     * Send a confirmation message to the user.
     *
     * @param $data
     */
    public function sendConfirmationContactMessage($data)
    {
        $from = [$this->from => $this->name];
        $to = [$data['email'] => $data['firstName'].' '.$data['lastName']];
        $subject = 'Reception of your message';
        $body = $this->templating->render('mail/confirmationContactMessage.html.twig', ['data' => $data]);

        $this->sendEmailMessage($from, $to, $subject, $body);
    }
}
