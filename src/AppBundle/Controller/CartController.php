<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Locus;
use AppBundle\Form\Type\CartType;
use AppBundle\Utils\CartManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @Security("is_granted('VIEW', locus.getChromosome().getStrain())")
     */
    public function addAction(Locus $locus, Request $request)
    {
        $cartManager = $this->get('AppBundle\Utils\CartManager');
        $cartManager->addToCart($locus);
        $cart = $cartManager->getCart();

        if (true === $cart['reached_limit']) {
            $this->addFlash('warning', 'You can store '.$cart['limit'].' elements maximum in your cart.');

            if (!$this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $this->addFlash('info', 'You can create an account to increase the limit to '.CartManager::AUTHENTICATED_NB_ITEMS.'.');
            }
        }

        // At the end, send a json for an xml request, or a redirection if it's an html request
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
        $cartManager = $this->get('AppBundle\Utils\CartManager');
        $cartManager->removeToCart($id);
        $cart = $cartManager->getCart();

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
        $cartManager = $this->get('AppBundle\Utils\CartManager');
        $cartManager->emptyCart();

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart", name="cart_view")
     */
    public function viewAction(Request $request)
    {
        $cartManager = $this->get('AppBundle\Utils\CartManager');
        $cartElements = $cartManager->getCartEntities();

        $form = $this->createForm(CartType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($cartElements)) {
                $this->addFlash('warning', 'The cart is empty, there is nothing to generate.');

                return $this->redirectToRoute('cart_view');
            }

            $data = $form->getData();
            $fasta = $cartManager->getCartFasta($data['type'], $data['feature'], $data['intronSplicing'], $data['upstream'], $data['downstream'], false);

            return $this->render('cart/fasta_generated.html.twig', [
                'fasta' => $fasta,
                'formData' => $data,
            ]);
        }

        return $this->render('cart/view.html.twig', [
            'cartElements' => $cartElements,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cart/download/{type}-{feature}-{intronSplicing}-{upstream}-{downstream}", name="cart_download",
     *     requirements={
     *          "type": "prot|nuc",
     *          "feature": "locus|feature|product",
     *          "intronSplicing": "0|1",
     *          "upstream": "\d+",
     *          "downstream": "\d+",
     *     },
     *     defaults={
     *          "type": "nuc",
     *          "feature": "locus",
     *          "intronSplicing": "0",
     *          "upstream": "0",
     *          "downstream": "0"
     *     }
     * )
     */
    public function downloadAction(string $type, string $feature, bool $intronSplicing, int $upstream, int $downstream)
    {
        $cartManager = $this->get('AppBundle\Utils\CartManager');
        $cartElements = $cartManager->getCartEntities();

        if (empty($cartElements)) {
            $this->addFlash('warning', 'The cart is empty, there is nothing to generate.');

            return $this->redirectToRoute('cart_view');
        }

        return $cartManager->streamCart($type, $feature, $intronSplicing, $upstream, $downstream);
    }

    /**
     * @Route("/cart/fasta", name="cart_fasta", condition="request.isXmlHttpRequest()")
     */
    public function fastaAction(Request $request)
    {
        // Add an explicit action, because this controller will be include in a view
        $form = $this->createForm(CartType::class, null, [
            'action' => $this->generateUrl('cart_fasta'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $cartManager = $this->get('AppBundle\Utils\CartManager');
            $fasta = $cartManager->getCartFasta($data['type'], $data['feature'], $data['intronSplicing'], $data['upstream'], $data['downstream']);

            return new Response($fasta);
        }

        return $this->render('cart/fasta_modal.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
