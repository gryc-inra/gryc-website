<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Locus;
use AppBundle\Entity\Product;

class InstanceOfExtension extends \Twig_Extension
{
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('locus', function ($event) {
                return $event instanceof Locus;
            }),
            new \Twig_SimpleTest('feature', function ($event) {
                return $event instanceof Feature;
            }),
            new \Twig_SimpleTest('product', function ($event) {
                return $event instanceof Product;
            }),
        ];
    }
}
