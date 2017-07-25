<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Locus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CartManager
{
    const ANONYMOUS_NB_ITEMS = 20;
    const AUTHENTICATED_NB_ITEMS = 100;

    private $session;
    private $authorizationChecker;
    private $cart;
    private $em;

    public function __construct(SessionInterface $session, AuthorizationCheckerInterface $authorizationChecker, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->authorizationChecker = $authorizationChecker;
        $this->initCart();
        $this->em = $entityManager;
    }

    public function addToCart(Locus $locus)
    {
        // Get the max nb of items
        $itemsLimit = $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ? self::AUTHENTICATED_NB_ITEMS : self::ANONYMOUS_NB_ITEMS;

        // If the cart already contains the max
        if (count($this->cart['items']) >= $itemsLimit) {
            // Switch the error key to true
            $this->cart['reached_limit'] = true;
        }
        // elseif the locus is not already in the cart, add it
        elseif (!in_array($locus->getId(), $this->cart['items'])) {
            $this->cart['items'][] = $locus->getId();
        }

        $this->saveCart();
    }

    public function removeToCart($id)
    {
        if (false !== $key = array_search($id, $this->cart['items'])) {
            unset($this->cart['items'][$key]);
            $this->cart['items'] = array_values($this->cart['items']);
            $this->saveCart();
        }
    }

    public function emptyCart()
    {
        $this->cart['items'] = [];

        $this->saveCart();
    }

    private function initCart()
    {
        $cart = $this->session->get('cart');

        if (!$cart) {
            $cart = [
                'items' => [],
            ];

            $this->session->set('cart', $cart);
        }

        $cart['reached_limit'] = false;

        $this->cart = $cart;
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function saveCart()
    {
        $this->session->set('cart', $this->cart);
    }

    public function getCartEntities()
    {
        return $this->em->getRepository('AppBundle:Locus')->findLocusById($this->cart['items']);
    }

    public function streamCart($form)
    {
        $data = $form->getData();

        $fileName = 'Gryc-cart-export-'.date('Y-m-d_h:i:s');
        $response = new StreamedResponse();
        $response->setCallback(function () use ($data) {
            $fastaGenerator = new FastaGenerator(true);
            $fastaGenerator->generateFasta($this->getCartEntities(), $data['type'], $data['feature'], $data['intronSplicing'], $data['upstream'], $data['downstream']);
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'.fasta"');

        return $response;
    }
}
