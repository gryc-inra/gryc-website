<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Form\Type\BlastType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlastController extends Controller
{
    /**
     * @Route("/blast", name="blast_index")
     */
    public function indexAction(Request $request)
    {
        // Retrieve data form the previous form in session
        $data = $request->getSession()->get('blast_form_data');
        $form = $this->createForm(BlastType::class, $data);

        // Get previous user blast
        if (null !== $this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $previousBlasts = $em->getRepository('AppBundle:Job')->findBy(['createdBy' => $this->getUser()], ['created' => 'DESC'], Job::NB_KEPT_JOBS);
        } else {
            $previousBlasts = null;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the data from the form
            $data = $form->getData();

            // Store data in Session, to autocomplete the form
            $request->getSession()->set('blast_form_data', $data);

            // Call Blast manager
            $blastManager = $this->get('app.blast_manager');
            $job = $blastManager->createJob($data);

            return $this->redirectToRoute('blast_job', [
                'name' => $job->getName(),
            ]);
        }

        return $this->render('blast/index.html.twig', [
            'form' => $form->createView(),
            'previousBlasts' => $previousBlasts,
        ]);
    }

    /**
     * @Route("/blast/job/{name}", name="blast_job")
     */
    public function viewAction(Job $job)
    {
        if (null !== $job->getResult() && !is_numeric($job->getResult())) {
            $result = $this->get('app.blast_manager')->xmlToArray($job->getResult());
        } else {
            $result = null;
        }

        return $this->render('blast/view.html.twig', [
            'job' => $job,
            'result' => $result,
        ]);
    }
}
