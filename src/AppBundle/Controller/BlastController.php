<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Form\Type\BlastType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlastController extends Controller
{
    /**
     * @Route("/blast", name="blast_index")
     * @Route("/blast/{job_name}", name="blast_index_prefilled")
     * @ParamConverter("job", options={"mapping": {"job_name": "name"}})
     */
    public function indexAction(Job $job = null, Request $request)
    {
        // Get blastManager
        $blastManager = $this->get('app.blast_manager');
        $data = (null === $job) ? $blastManager->getLastBlastForm() : $blastManager->getBlastForm($job);

        $form = $this->createForm(BlastType::class, $data);

        // Get previous user blast
        $em = $this->getDoctrine()->getManager();
        if (null !== $this->getUser()) {
            $previousBlasts = $em->getRepository('AppBundle:Job')->findBy(['createdBy' => $this->getUser()], ['created' => 'DESC'], Job::NB_KEPT_JOBS);
        } else {
            $previousBlasts = null;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

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
            $result = $this->get('app.blast_manager')->xmlToArray($job->getResult(), $job->getFormData());
        } else {
            $result = null;
        }

        return $this->render('blast/view.html.twig', [
            'job' => $job,
            'result' => $result,
        ]);
    }
}
