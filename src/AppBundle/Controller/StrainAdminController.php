<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Strain;
use AppBundle\Form\Type\StrainRightsType;
use AppBundle\Form\Type\StrainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Strain administration controller.
 */
class StrainAdminController extends Controller
{
    /**
     * @Route("/admin/strain", name="strain_admin_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $strains = $em->getRepository('AppBundle:Strain')->getStrainsWithSpeciesAndClade();

        return $this->render('strain/admin/index.html.twig', [
            'strains' => $strains,
        ]);
    }

    /**
     * @Route("/admin/strain/{id}/edit", name="strain_edit")
     */
    public function editAction(Request $request, Strain $strain)
    {
        $form = $this->createForm(StrainType::class, $strain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The strain was successfully edited.');

            return $this->redirectToRoute('strain_admin_index');
        }

        return $this->render('strain/admin/edit.html.twig', [
            'strain' => $strain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/admin/strain/{id}/user-rights", name="strain_user_rights")
     */
    public function userRightsAction(Request $request, Strain $strain)
    {
        if ($strain->isPublic()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(StrainRightsType::class, $strain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'The user\'s rights for the strain '.$strain->getName().' were successfully edited.');

            return $this->redirectToRoute('strain_admin_index');
        }

        return $this->render('strain/admin/userRights.html.twig', [
            'strain' => $strain,
            'form' => $form->createView(),
        ]);
    }
}
