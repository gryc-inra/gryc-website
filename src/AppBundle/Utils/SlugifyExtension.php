<?php

// src/AppBundle/Utils/SlugifyExtension.php

namespace AppBundle\Utils;

class SlugifyExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('slugify', [$this, 'slugify']),
        ];
    }

    public function slugify($text)
    {
        //replace %20
        $text = preg_replace('#%20#', '-', $text);

        //replace non letters, digit or hyphens by hyphen
        $text = preg_replace('#\W-#', '-', $text);

        //trim the text and lowercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }

    public function getName()
    {
        return 'slugify_extension';
    }
}
