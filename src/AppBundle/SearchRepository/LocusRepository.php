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

namespace AppBundle\SearchRepository;

use AppBundle\Entity\User;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\HasParent;
use Elastica\Query\Match;
use Elastica\Query\MultiMatch;
use Elastica\Query\Nested;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use FOS\ElasticaBundle\Repository;

class LocusRepository extends Repository
{
    const HITS_PER_PAGE = 50;

    public function findByNameNoteAnnotation($keyword = null, User $user = null, $strains = null)
    {
        // Create the Query
        $query = new BoolQuery();
        $query->setMinimumShouldMatch(1);

        // LOCUS
        $locusQuery = new BoolQuery();
        $locusQuery->setMinimumShouldMatch(1);
        $query->addShould($locusQuery);

        $locusNameQuery = new MultiMatch();
        $locusNameQuery->setQuery($keyword);
        $locusNameQuery->setFields(['name^2', 'name.ngramed']);
        $locusQuery->addShould($locusNameQuery);

        $locusNoteQuery = new MultiMatch();
        $locusNoteQuery->setFields(['note^2', 'note.stemmed']);
        $locusNoteQuery->setFuzziness('AUTO');
        $locusNoteQuery->setQuery($keyword);
        $locusQuery->addShould($locusNoteQuery);

        $locusGeneQuery = new Match();
        $locusGeneQuery->setFieldQuery('annotation.gene', $keyword);
        $locusQuery->addShould($locusGeneQuery);

        // FEATURES
        $featureNested = new Nested();
        $featureNested->setPath('features');
        $featureNested->setScoreMode('avg');
        $query->addShould($featureNested);

        $featureQuery = new BoolQuery();
        $featureQuery->setMinimumShouldMatch(1);
        $featureNested->setQuery($featureQuery);

        $featureNameQuery = new MultiMatch();
        $featureNameQuery->setQuery($keyword);
        $featureNameQuery->setFields(['features.name^2', 'features.name.ngramed']);
        $featureQuery->addShould($featureNameQuery);

        $featureNoteQuery = new MultiMatch();
        $featureNoteQuery->setFields(['features.note^2', 'features.note.stemmed']);
        $featureNoteQuery->setFuzziness('AUTO');
        $featureNoteQuery->setQuery($keyword);
        $featureQuery->addShould($featureNoteQuery);

        $featureGeneQuery = new Match();
        $featureGeneQuery->setFieldQuery('features.annotation.gene', $keyword);
        $locusQuery->addShould($featureGeneQuery);

        // PRODUCTS
        $featureProductNested = new Nested();
        $featureProductNested->setPath('features');
        $featureProductNested->setScoreMode('avg');
        $query->addShould($featureProductNested);

        $productNested = new Nested();
        $productNested->setPath('features.products');
        $productNested->setScoreMode('avg');
        $featureProductNested->setQuery($productNested);

        $productQuery = new BoolQuery();
        $productQuery->setMinimumShouldMatch(1);
        $productNested->setQuery($productQuery);

        $productNameQuery = new MultiMatch();
        $productNameQuery->setQuery($keyword);
        $productNameQuery->setFields(['features.products.name^2', 'features.products.name.ngramed']);
        $productQuery->addShould($productNameQuery);

        $productNoteQuery = new MultiMatch();
        $productNoteQuery->setFields(['features.products.note^2', 'features.products.note.stemmed']);
        $productNoteQuery->setFuzziness('AUTO');
        $productNoteQuery->setQuery($keyword);
        $productQuery->addShould($productNoteQuery);

        $productGeneQuery = new Match();
        $productGeneQuery->setFieldQuery('features.products.annotation.gene', $keyword);
        $productQuery->addShould($productGeneQuery);

        // FILTER
        // Create a BoolQuery with filters
        $boolFilter = new BoolQuery();
        $boolFilter->setMinimumShouldMatch(1);
        $query->addFilter($boolFilter);

        $userId = null !== $user ? $user->getId() : '';
        // Set a user filter
        $userFilter = new Term();
        $userFilter->setTerm('usersId', $userId);
        $parentUserFilterQuery = new HasParent($userFilter, 'strain');
        $boolFilter->addShould($parentUserFilterQuery);

        // Set a public filter
        $publicFilter = new Term();
        $publicFilter->setTerm('public', true);
        $parentPublicFilterQuery = new HasParent($publicFilter, 'strain');
        $boolFilter->addShould($parentPublicFilterQuery);

        // If the user set a list of strain, create a filter
        if (null !== $strains) {
            $strainsId = [];
            // Prepare strainsIdArray
            foreach ($strains as $strain) {
                $strainsId[] = $strain->getId();
            }

            $strainsFilter = new Terms();
            $strainsFilter->setTerms('id', $strainsId);
            $parentStrainsFilterQuery = new HasParent($strainsFilter, 'strain');
            $query->addFilter($parentStrainsFilterQuery);
        }

        // Execute the query
        return $this->find($query, self::HITS_PER_PAGE);
    }
}
