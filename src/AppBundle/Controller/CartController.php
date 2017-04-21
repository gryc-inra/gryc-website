<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Locus;
use AppBundle\Form\Type\CartType;
use AppBundle\Utils\FastaGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CartController extends Controller
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @Security("is_granted('VIEW', locus.getChromosome().getStrain())")
     */
    public function addAction(Locus $locus, Request $request)
    {
        $session = $request->getSession();

        // Retrieve the cart, else create it
        if (!$cart = $session->get('cart')) {
            $cart = [];
        }

        if (!in_array($locus->getId(), $cart)) {
            $cart[] = $locus->getId();
            $session->set('cart', $cart);
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($cart);
        } else {
            return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function removeAction($id, Request $request)
    {
        $session = $request->getSession();

        // Retrieve the cart, else create it
        if (!$cart = $session->get('cart')) {
            $cart = [];
        }

        if (false !== $key = array_search($id, $cart)) {
            unset($cart[$key]);
            $cart = array_values($cart);
            $session->set('cart', $cart);
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($cart);
        } else {
            return $this->redirectToRoute('cart_view');
        }
    }

    /**
     * @Route("/cart/empty", name="cart_empty")
     */
    public function emptyAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('cart', []);

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart", name="cart_view")
     */
    public function viewAction(Request $request)
    {
        $cart = $request->getSession()->get('cart');
        $em = $this->getDoctrine()->getManager();
        $cartElements = $em->getRepository('AppBundle:Locus')->findLocusById($cart);

        $form = $this->createForm(CartType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = 'Gryc-cart-export-'.date('Y-m-d_h:i:s');

            $response = new StreamedResponse();
            $response->setCallback(function () use ($form, $cartElements) {
                $fastaGenerator = new FastaGenerator();
                $fastaGenerator->generateFasta($form->getData(), $cartElements);
            });

            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'.fasta"');

            return $response;
        }

        return $this->render('cart/view.html.twig', [
            'cartElements' => $cartElements,
            'form' => $form->createView(),
        ]);
    }
}
