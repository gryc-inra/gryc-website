<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Reference;
use Doctrine\ORM\EntityManagerInterface;

class ReferenceManager
{
    /**
     * A constant that contain the api url.
     */
    const DOI_API_LINK = 'http://doi.org/';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function doiToArray(string $doi)
    {
        // Define the uri and do a GET request on
        $uri = self::DOI_API_LINK.$doi;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $uri, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        // Retrieve result and convert json in array
        $json = $res->getBody()->getContents();
        $array = json_decode($json);

        return $array;
    }

    private function populateReference(\AppBundle\Entity\Reference $reference, string $doi)
    {
        $data = $this->doiToArray($doi);

        // Set reference attributes
        $reference->setAuthors(json_decode(json_encode($data->author), true));
        $reference->setContainer($data->{'container-title-short'});
        $reference->setUrl($data->URL);
        $reference->setIssued($data->issued->{'date-parts'}[0][0]);
        $reference->setDoi($data->DOI);

        return $reference;
    }

    public function getReference(string $doi)
    {
        // Check if a reference already exists
        $reference = $this->entityManager->getRepository('AppBundle:Reference')->findOneByDoi($doi);

        if (null === $reference) {
            $reference = new Reference();
            $reference = $this->populateReference($reference, $doi);
        }

        return $reference;
    }
}
