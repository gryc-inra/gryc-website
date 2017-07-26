<?php

// src/AppBundle/Utils/Mailer.php

namespace AppBundle\Utils;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Mailer
 */
class Mailer
{
    protected $mailer;
    protected $templating;
    private $from = 'gryc.inra@gmail.com';
    private $name = 'GRYC - The yeast genomics database';

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
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
    protected function sendEmailMessage($from, $replyTo = null, $to, $subject, $body)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom($from)
            ->setTo($to)
            ->setFrom($from)
            ->setReplyTo($replyTo)
            ->setSubject($subject)
            ->setBody($body)
            ->setCharset('utf-8')
            ->setContentType('text/html');

        return $this->mailer->send($message);
    }


    /**
     * Send the contact message.
     *
     * @param $data
     */
    public function sendContactMessage($data)
    {
        $from = [$data['email'] => $data['firstName'].' '.$data['lastName']];
        $replyTo = $from;
        $to = [$this->from => $this->name];
        $subject = '[GRYC Contact]['.$data['category'].'] '.$data['subject'];
        $body = $this->templating->render('mail/contactMessage.html.twig', ['data' => $data]);

        $this->sendEmailMessage($from, $replyTo, $to, $subject, $body);

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

        $this->sendEmailMessage($from, null, $to, $subject, $body);
    }
}
