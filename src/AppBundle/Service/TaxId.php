<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
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

use Symfony\Component\DomCrawler\Crawler;

class TaxId
{
    /**
     * A constant that contain the api url.
     */
    const NCBI_TAXONOMY_API_LINK = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=taxonomy&id=';

    public function getArray(int $taxid)
    {
        // Initialise the response
        $response = [];

        // Check the URL, and get the file
        $url = self::NCBI_TAXONOMY_API_LINK.$taxid;

        // Get the header array
        $headers = get_headers($url);

        // Parse the header array to get the last HTTP status code
        $statusCode = null;
        foreach (array_reverse($headers) as $header) {
            if ('HTTP/' === mb_substr($header, 0, 5)) {
                $httpStatus = explode(' ', $header, 3);
                $statusCode = (int) $httpStatus[1];

                break;
            }
        }

        // Check the status value
        if (null === $statusCode) {
            $response['error'] = 'No answer retrieved from the NCBI API';

            return $response;
        } elseif (200 !== $statusCode) {
            $response['error'] = 'The NCBI API returns a non-200 response (Status: '.$statusCode.' error)';

            return $response;
        }

        // HTTP status is 200, get the page content
        $xmlString = file_get_contents($url);

        // Create a crawler and give the xml code to it
        $crawler = new Crawler($xmlString);

        // Count the number of taxon tag, if different of 0 there are contents, else the document is empty, it's because the Taxon Id doesn't exists
        if (0 !== $crawler->filterXPath('//TaxaSet/Taxon')->count()) {
            // If the tag Rank contain 'species', the Id match on a species, else, it's not correct.
            if ('species' === $crawler->filterXPath('//TaxaSet/Taxon/Rank')->text()) {
                // Use the crawler to crawl the document and fill the response
                $response['scientificName'] = $crawler->filterXPath('//TaxaSet/Taxon/ScientificName')->text();

                // Explode the scientific name to retrieve: genus and species
                $scientificNameExploded = explode(' ', $response['scientificName']);
                $response['genus'] = $scientificNameExploded[0];
                $response['species'] = $scientificNameExploded[1];

                $response['geneticCode'] = $crawler->filterXPath('//TaxaSet/Taxon/GeneticCode/GCId')->text();
                $response['mitoCode'] = $crawler->filterXPath('//TaxaSet/Taxon/MitoGeneticCode/MGCId')->text();
                $response['lineages'] = explode('; ', $crawler->filterXPath('//TaxaSet/Taxon/Lineage')->text());

                // He re count the number of synonym tag, if the count is different to 0, there are synonyms
                if (0 !== $crawler->filterXPath('//TaxaSet/Taxon/OtherNames/Synonym')->count()) {
                    // Use a closure on the tag Synonym to extract all synonyms and fill an array
                    $synonyms = $crawler->filterXPath('//TaxaSet/Taxon/OtherNames/Synonym')->each(function (Crawler $node) {
                        return $node->text();
                    });
                    $response['synonyms'] = $synonyms;
                }
            } else {
                $response['error'] = 'This ID does not match on a species';
            }
        } else {
            $response['error'] = 'This ID does not exists';
        }

        return $response;
    }
}
