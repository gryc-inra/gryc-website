<?php

namespace AppBundle\SearchRepository;

use FOS\ElasticaBundle\Repository;

class UserRepository extends Repository
{
    public function findWithCustomQuery($searchText)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $fieldQuery = new \Elastica\Query\Match();
        $fieldQuery->setFieldQuery('lastName', $searchText);
        $fieldQuery->setFieldParam('lastName', 'analyzer', 'custom_search_analyzer');
        $boolQuery->addShould($fieldQuery);

        //$tagsQuery = new \Elastica\Query\Terms();
        //$tagsQuery->setTerms('tags', array('tag1', 'tag2'));
        //$boolQuery->addShould($tagsQuery);

        // build $query with Elastica objects
        return $this->find($boolQuery);
    }
}
