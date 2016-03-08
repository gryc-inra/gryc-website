<?php

namespace Grycii\AppBundle\SearchRepository;

use FOS\ElasticaBundle\Repository;

class UserRepository extends Repository
{
    public function findWithCustomQuery($searchText)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $fieldQuery = new \Elastica\Query\Match();
        $fieldQuery->setFieldQuery('username', $searchText);
        $fieldQuery->setFieldParam('username', 'analyzer', 'custom_search_analyzer');
        $boolQuery->addShould($fieldQuery);

        $fieldQuery2 = new \Elastica\Query\Match();
        $fieldQuery2->setFieldQuery('firstName', $searchText);
        $fieldQuery2->setFieldParam('firstName', 'analyzer', 'custom_search_analyzer');
        $boolQuery->addShould($fieldQuery2);

        $fieldQuery3 = new \Elastica\Query\Match();
        $fieldQuery3->setFieldQuery('lastName', $searchText);
        $fieldQuery3->setFieldParam('lastName', 'analyzer', 'custom_search_analyzer');
        $boolQuery->addShould($fieldQuery3);

        $fieldQuery4 = new \Elastica\Query\Match();
        $fieldQuery4->setFieldQuery('email', $searchText);
        $fieldQuery4->setFieldParam('email', 'analyzer', 'custom_search_analyzer');
        $boolQuery->addShould($fieldQuery4);

        // build $query with Elastica objects
        return $this->find($boolQuery);
    }
}
