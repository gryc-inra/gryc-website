<?php
// src/AppBundle/Controller/StrainController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Strain;
use AppBundle\Form\Type\StrainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

class StrainController extends Controller
{
    /**
     * @Route("species/{speciesSlug}/{strainSlug}", name="strain_view")
     * @ParamConverter("strain", class="AppBundle:Strain", options={
     *     "repository_method": "getStrainWithFlatFiles",
     *     "mapping": {"strainSlug": "slug"},
     *     "map_method_signature" = true
     * })
     * @Security("is_granted('VIEW', strain)")
     */
    public function viewAction(Strain $strain)
    {
        return $this->render('strain/view.html.twig', array(
            'strain' => $strain,
        ));
    }

    /**
     * @Route("/admin/strain/list", name="strain_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $strains = $em->getRepository('AppBundle:Strain')->getStrainsWithSpecies();

        return $this->render('strain/list.html.twig', array(
            'strains' => $strains,
        ));
    }

    /**
     * @Route("/admin/strain/{id}/edit", name="strain_edit")
     */
    public function editAction(Request $request, Strain $strain)
    {
        $form = $this->createForm(StrainType::class, $strain);
        $form->add('save', SubmitType::class, array(
            'label' => 'Edit the strain',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The strain was successfully edited.');

            return $this->redirectToRoute('strain_list');
        }

        return $this->render('strain/edit.html.twig', array(
            'strain' => $strain,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/strain/{id}/delete", name="strain_delete")
     */
    public function deleteAction(Request $request, Strain $strain)
    {
        $form = $this->createFormBuilder()
            ->add('confirm', TextType::class, array(
                'constraints' => new Regex('#^I confirm the deletion$#'),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($strain);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The strain was successfully deleted.');

            return $this->redirectToRoute('species_list');
        }

        return $this->render('strain/delete.html.twig', array(
            'strain' => $strain,
            'form' => $form->createView(),
        ));
    }
}
