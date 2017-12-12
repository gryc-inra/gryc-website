<?php

namespace AppBundle\SearchRepository;

use AppBundle\Entity\User;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MultiMatch;
use Elastica\Query\Nested;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Elastica\Query\HasParent;
use FOS\ElasticaBundle\Repository;

class LocusRepository extends Repository
{
    const HITS_PER_PAGE = 50;

    public function findByNameNoteAnnotation($keyword = null, User $user = null, $strains = null)
    {
        // Create the Query
        $query = new BoolQuery();
        $query->setMinimumNumberShouldMatch(1);

        // LOCUS
        $locusQuery = new BoolQuery();
        $locusQuery->setMinimumNumberShouldMatch(1);
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
        $featureQuery->setMinimumNumberShouldMatch(1);
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
        $productQuery->setMinimumNumberShouldMatch(1);
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
        $boolFilter->setMinimumNumberShouldMatch(1);
        $query->addFilter($boolFilter);

        $userId = null !== $user ? $user->getId() : '';
        // Set a user filter
        $userFilter = new Term();
        $userFilter->setTerm('authorizedUsersId', $userId);
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
