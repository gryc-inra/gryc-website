<?php

namespace AppBundle\Utils;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Sitemap
{
    private $router;
    private $em;
    private $filesystem;
    private $twig;

    public function __construct(RouterInterface $router, ObjectManager $em, Filesystem $filesystem, \Twig_Environment $twig)
    {
        $this->router = $router;
        $this->em = $em;
        $this->filesystem = $filesystem;
        $this->twig = $twig;
    }

    public function generate()
    {
        // Count the number of urls
        $nbLocus = $this->em->getRepository('AppBundle:Locus')->countPublicLocus();

        // How many files we need ? (50000 urls max per file)
        $nbUrlsMax = 50000;
        $nbFiles = ceil($nbLocus / $nbUrlsMax);

        // Create the sitemaps files
        for ($i = 0; $i < $nbFiles; ++$i) {
            $offset = ($i * $nbUrlsMax);
            $limit = $nbUrlsMax - 1;
            $urls = $this->generateLocusUrls($offset, $limit);
            $this->filesystem->dumpFile('web/sitemap'.($i + 1).'.xml', $this->twig->render('seo/sitemap.xml.twig', ['urls' => $urls]));
        }

        // Create the sitemap index
        $this->filesystem->dumpFile('web/sitemap.xml',
            $this->twig->render('seo/sitemap_index.xml.twig', [
                'nbFiles' => $nbFiles,
                'url' => $this->router->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ])
        );
    }

    private function generateLocusUrls($offset, $limit)
    {
        $urls = [];

        $locusList = $this->em->getRepository('AppBundle:Locus')->findPublicLocus($offset, $limit);

        foreach ($locusList as $locus) {
            $urls[] = [
                'loc' => $this->router->generate('locus_view', [
                    'species_slug' => $locus->getChromosome()->getStrain()->getSpecies()->getSlug(),
                    'strain_slug' => $locus->getChromosome()->getStrain()->getSlug(),
                    'chromosome_slug' => $locus->getChromosome()->getSlug(),
                    'locus_name' => $locus->getName(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }

        return $urls;
    }
}
