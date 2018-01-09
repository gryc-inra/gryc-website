<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Service;

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
        $this->cart['limit'] = $itemsLimit;

        // If the cart already contains the max
        if (count($this->cart['items']) >= $itemsLimit) {
            // Switch the error key to true
            $this->cart['reached_limit'] = true;
        }
        // elseif the locus is not already in the cart, add it
        elseif (!in_array($locus->getId(), $this->cart['items'], true)) {
            $this->cart['items'][] = $locus->getId();
        }

        $this->saveCart();
    }

    public function removeToCart(int $id)
    {
        if (false !== $key = array_search($id, $this->cart['items'], true)) {
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

    public function getCartFasta($type = 'nuc', $feature = 'locus', $intronSplicing = false, $upstream = 0, $downstream = 0, $stream = false)
    {
        $fastaGenerator = new FastaGenerator($stream);

        return $fastaGenerator->generateFasta($this->getCartEntities(), $type, $feature, $intronSplicing, $upstream, $downstream);
    }

    public function streamCart($type = 'nuc', $feature = 'locus', $intronSplicing = false, $upstream = 0, $downstream = 0)
    {
        $fileName = 'Gryc-cart-export-'.date('Y-m-d_h:i:s');
        $response = new StreamedResponse();
        $response->setCallback(function () use ($type, $feature, $intronSplicing, $upstream, $downstream) {
            $this->getCartFasta($type, $feature, $intronSplicing, $upstream, $downstream, true);
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'.fasta"');

        return $response;
    }
}
