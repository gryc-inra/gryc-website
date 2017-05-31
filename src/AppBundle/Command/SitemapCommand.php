<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seo:sitemap:generate')
            ->setDescription('Generate the sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>The generation of the sitemap file start. This may take some times.</comment>');

        $this->getContainer()->get('app.sitemap')->generate();
    }
}
