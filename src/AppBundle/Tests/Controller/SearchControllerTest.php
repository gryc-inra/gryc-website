<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testQuicksearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/quickSearch');
    }

}
