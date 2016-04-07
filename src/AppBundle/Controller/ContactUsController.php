<?php
// src/AppBundle/Controller/ContactUsController.php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\Type\ContactUsType;
use AppBundle\Form\Type\ContactUsReplyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * "Contact us" controller.
 *
 * @Route("/contact")
 */
class ContactUsController extends Controller
{
    /**
     * Display a contact form for the visitor.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="contact_us")
     */
    public function contactAction(Request $request)
    {
        $contactus = new ContactUs();

        // Si le visiteur est connecté, définir une partie des attributs de l'objet ContactUs
        if ($user = $this->getUser()) {
            $contactus->setFirstName($user->getFirstName());
            $contactus->setLastName($user->getLastName());
            $contactus->setEmail($user->getEmailCanonical());
        }

        // Initialiser le formulaire en lui fournissant l'objet, et en définissant le champ de validation
        $form = $this->createForm(ContactUsType::class, $contactus);
        $form->add('save', SubmitType::class, array(
            'label' => 'Send message',
        ));

        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isValid()) {
            // Envoyer un email en utilisant le service app.mailer, et la méthode correspondante
            $this->get('app.mailer')->sendConfirmationContactEmailMessage($contactus);

            // Persister l'objet en base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactus);
            $em->flush();

            // Créer un flash bag avec un message de succès, puis rediriger l'utilisateur sur la page d'accueil
            $this->addFlash('success', 'Your message has been submitted.');

            return $this->redirectToRoute('homepage');
        }

        // Si le formulaire n'est pas valide, ou si c'est la première vue de la page, afficher le formulaire
        return $this->render('contactus\contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * List all messages posted by visitors.
     *
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/list/{page}", name="contact_us_homepage", defaults={"page": 1}, requirements={"page": "\d*"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction($page)
    {
        // Vérifier que la page demandée existe
        if (0 === $page) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        // Définir le nombre de messages par page
        $nbPerPage = 10;

        // Récupérer les messages à afficher, et le nombre d'objets persistés en base
        $listMessages = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:ContactUs')
            ->getMessages($page, $nbPerPage);

        // Calculer le nombre de page, grâce à la liste des messages
        $nbPages = ceil(count($listMessages) / $nbPerPage);

        // Définir le nombre de page qu'il doit y avoir, et vérifier que la page demandée ne soit pas au dessus du nombre de pages
        if ($page > $nbPages && $page != 1) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        // Afficher la liste des messages
        return $this->render('contactus\index.html.twig', array(
            'listMessages' => $listMessages,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }

    /**
     * Delete a message.
     *
     * @param ContactUs $message
     * @param Request   $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete/{id}", name="contact_us_delete", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(ContactUs $message, Request $request)
    {
        // Créer un formulaire vide, qui ne contiendra qu'un unique champ hidden avec un jeton CSRF
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        // Si l'utilisateur confirme la suppression du message
        if ($form->isValid()) {
            // Supprimer l'objet de la base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            // Créer un message de succès et rediriger l'utilisateur vers la liste des messages
            $this->addFlash('success', 'The message has been deleted.');

            return $this->redirectToRoute('contact_us_homepage');
        }

        // Si le formulaire n'est pas valide, ou si c'est la première vue de la page, afficher le formulaire
        return $this->render('contactus\delete.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reply/{id}", name="contact_us_reply", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */

    /**
     * Reply to a message.
     *
     * @param ContactUs $message
     * @param Request   $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/reply/{id}", name="contact_us_reply", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function replyAction(ContactUs $message, Request $request)
    {
        // Préparer la réponse à la question posée
        $answer = 'Dear '.$message->getFirstName().' '.$message->getLastName().','.chr(10);
        $answer .= 'First of all, we want to thank you for your interest in Gryc.'.chr(10);
        $answer .= chr(10).chr(10);
        $answer .= 'Best Regards,'.chr(10);
        $answer .= 'The GRYC team'.chr(10);
        $answer .= chr(10);
        $answer .= 'Postscript: If you have any questions about this issue, you can contact me at this mail: '.$this->getUser()->getEmailCanonical();

        // Générer le formulaire, en lui donnant la réponse préparée
        $data = array('answer' => $answer);
        $form = $this->createForm(ContactUsReplyType::class, $data);

        $form->handleRequest($request);

        // Si l'utilisateur a rempli le formulaire et qu'il est bien rempli
        if ($form->isValid()) {
            // $data est un array avec une clé answer, et une valeur qui est la réponse
            $data = $form->getData();

            // Définir le nom et l'email de la personne qui répond, en récupérant les données de l'utilisateur
            $fromName = $this->getUser()->getFirstName().' '.$this->getUser()->getLastName();
            $fromMail = $this->getUser()->getEmailCanonical();

            // Appeller le service app.mailer pour envoyer le mail à la personne ayant posé la question
            $this->get('app.mailer')->sendReplyContactEmailMessage($message, $data, $fromName, $fromMail);

            // Supprimer le message de la base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            // Créer un message de succès et rediriger l'utilisateur vers la liste des messages
            $this->addFlash('success', 'Your message has been send.');

            return $this->redirectToRoute('contact_us_homepage');
        }

        // Sinon, on affiche le formulaire
        return $this->render('contactus\reply.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }

    /**
     * Count the number of messages.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function numberMessageAction()
    {
        // Compter le nombe de message dans la base de données
        $em = $this->getDoctrine()->getManager();
        $nbMessages = $em->getRepository('AppBundle:ContactUs')->getNumberMessages();

        // Afficher le nombre de messages
        return $this->render('contactus\numberMessages.html.twig', array(
            'nbMessages' => $nbMessages,
        ));
    }
}
