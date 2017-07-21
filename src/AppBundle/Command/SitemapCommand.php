<?php

namespace AppBundle\Command;

use AppBundle\Utils\Sitemap;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand extends ContainerAwareCommand
{
    private $sitemap;

    public function __construct(Sitemap $sitemap)
    {
        $this->sitemap = $sitemap;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('seo:sitemap:generate')
            ->setDescription('Generate the sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>The generation of the sitemap file start. This may take some times.</comment>');

        $this->sitemap->generate();
    }
}
