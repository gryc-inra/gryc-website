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

namespace App\Service;

use App\Entity\Reference;
use Doctrine\ORM\EntityManagerInterface;

class ReferenceManager
{
    /**
     * A constant that contain the api url.
     */
    const DOI_API_LINK = 'http://doi.org/';

    private $entityManager;
    private $response;
    private $document;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isValidDoi(string $doi = null)
    {
        if (null !== $doi) {
            $this->resolveDoiUrl($doi);
        }

        if ($this->isValidUrl()) {
            return true;
        }

        return false;
    }

    public function getReference(string $doi)
    {
        // Check if a reference already exists
        $reference = $this->entityManager->getRepository('App:Reference')->findOneByDoi($doi);

        if (null === $reference) {
            $reference = new Reference();
            $reference = $this->populateReference($reference, $doi);
        }

        return $reference;
    }

    private function resolveDoiUrl(string $doi)
    {
        // Define the uri and do a GET request on
        $uri = self::DOI_API_LINK.$doi;

        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('GET', $uri, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'exceptions' => false,
        ]);

        // Retrieve result and convert json in array
        $json = $this->response->getBody()->getContents();
        $this->document = json_decode($json);
    }

    private function isValidUrl()
    {
        // Check if the status code is 200 or different
        if (200 !== $this->response->getStatusCode()) {
            return false;
        }

        return true;
    }

    private function populateReference(Reference $reference, string $doi)
    {
        $this->resolveDoiUrl($doi);
        if (!$this->isValidDoi()) {
            throw new \RuntimeException();
        }
        $data = $this->document;

        // Set reference attributes
        $reference->setAuthors(json_decode(json_encode($data->author), true));
        $reference->setContainer($data->{'container-title-short'});
        $reference->setUrl($data->URL);
        $reference->setIssued($data->issued->{'date-parts'}[0][0]);
        $reference->setDoi($data->DOI);

        return $reference;
    }
}
