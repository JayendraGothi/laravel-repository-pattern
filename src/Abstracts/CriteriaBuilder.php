<?php

namespace RepositoriesPattern\Abstracts;


use RepositoriesPatter\Contracts\Criteria;
use RepositoriesPattern\CommonCriteria\WhereFilter;
use RepositoriesPattern\CommonCriteria\WithFilter;
use RepositoriesPattern\Service;

class CriteriaBuilder {

    /**
     * Criteria list
     *
     * @var \Illuminate\Support\Collection
     */
    private $criteriaCollection;

    /**
     * CriteriaBuilder constructor
     *
     * @param Service $service
     * @param array $with
     */
    public function __construct(Service $service = null, $with = []) {
        $this->criteriaCollection = collect();

        // if service is set add basic filters
        if (isset($service))
            $service->addBasicFilters($this);

        // adding with filter
        if (isset($with) && sizeof($with) > 0)
            $this->add(new WithFilter($with));
    }

    /**
     * Adding common filters
     *
     * @param array $commonFilters
     */
    public function addCommonFilters($commonFilters = []) {
        foreach ($commonFilters as $key => $value) {
            $this->add(new WhereFilter($key, $value));
        }
    }

    /**
     * Add new Criteria
     *
     * @param Criteria $criteria
     * @return $this
     */
    public function add(Criteria $criteria) {
        $this->criteriaCollection->push($criteria);
        return $this;
    }

    /**
     * Get Criteria Collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCriterias() {
        return $this->criteriaCollection;
    }
}