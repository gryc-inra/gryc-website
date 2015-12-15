<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\ContactUsType;
use AppBundle\Form\ContactUsReplyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ContactUsController.
 *
 * @Route("/contact")
 */
class ContactUsController extends Controller
{
    /**
     * @Route("/", name="contact_us")
     */
    public function contactAction(Request $request)
    {
        $contactus = new ContactUs();

        if ($user = $this->getUser()) {
            $contactus->setFirstName($user->getFirstName());
            $contactus->setLastName($user->getLastName());
            $contactus->setEmail($user->getEmailCanonical());
        }

        $form = $this->createForm(ContactUsType::class, $contactus);
        $form->add('save', SubmitType::class, array(
            'label' => 'Send message',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.mailer')->sendConfirmationContactEmailMessage($contactus);

            $em = $this->getDoctrine()->getManager();
            $em->persist($contactus);
            $em->flush();

            $this->addFlash('success', 'Your message has been submitted.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('contactus\contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/list/{page}", name="contact_us_homepage", defaults={"page": 1}, requirements={"page": "\d*"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction($page)
    {
        if (0 === $page) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        $nbPerPage = 10;

        $listMessages = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:ContactUs')
            ->getMessages($page, $nbPerPage);

        $nbPages = ceil(count($listMessages) / $nbPerPage);

        if ($page > $nbPages && $page != 1) {
            throw $this->createNotFoundException("This page doesn't exist.");
        }

        return $this->render('contactus\index.html.twig', array(
            'listMessages' => $listMessages,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }

    /**
     * @Route("/delete/{id}", name="contact_us_delete", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(ContactUs $message, Request $request)
    {
        // Créer un formulaire vide (jeton CSRF), pour demander confirmation de la suppression
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'The message has been deleted.');

            return $this->redirectToRoute('contact_us_homepage');
        }

        return $this->render('contactus\delete.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reply/{id}", name="contact_us_reply", requirements={"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function replyAction(ContactUs $message, Request $request)
    {
        // On prépare la réponse à la question posée
        $answer = 'Dear '.$message->getFirstName().' '.$message->getLastName().','.chr(10);
        $answer .= 'First of all, we want to thank you for your interest in Gryc.'.chr(10);
        $answer .= chr(10).chr(10);
        $answer .= 'Best Regards,'.chr(10);
        $answer .= 'The GRYC team'.chr(10);
        $answer .= chr(10);
        $answer .= 'Postscript: If you have any questions about this issue, you can contact me at this mail: '.$this->getUser()->getEmailCanonical();

        $data = array('answer' => $answer);
        $form = $this->createForm(ContactUsReplyType::class, $data);

        $form->handleRequest($request);

        // Si l'utilisateur a rempli le formulaire et qu'il est bien rempli
        if ($form->isValid()) {
            // $data est un array avec une clé $message, et une valeur qui est le message
            $data = $form->getData();

            // On défini le nom et l'email de la personne qui répond, en récupérant les données de l'utilisateur
            $fromName = $this->getUser()->getFirstName().' '.$this->getUser()->getLastName();
            $fromMail = $this->getUser()->getEmailCanonical();

            // Appeller le service app.mailer pour envoyer le mail à la personne ayant posé la question
            $this->get('app.mailer')->sendReplyContactEmailMessage($message, $data, $fromName, $fromMail);

            // Après envoi du mail, on supprime le message de la base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            // On crée un flashbag pour signaler que tout s'est bien passé à l'utilisateur
            $this->addFlash('success', 'Your message has been send.');

            // Puis on le redirige vers la liste des messages
            return $this->redirectToRoute('contact_us_homepage');
        }

        // Sinon, on affiche le formulaire
        return $this->render('contactus\reply.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }

    /*
     * Compter le nombres de messages présent dans la base de données, et retourner juste le nombre de messages dans une vue
     */
    public function numberMessageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $nbMessages = $em->getRepository('AppBundle:ContactUs')->getNumberMessages();

        return $this->render('contactus\numberMessages.html.twig', array(
            'nbMessages' => $nbMessages,
        ));
    }
}
