<?php

namespace Reva\RepositoryPattern\Library\Abstracts;


use Reva\RepositoryPattern\Library\CommonCriteria\WhereFilter;
use Reva\RepositoryPattern\Library\CommonCriteria\WithFilter;
use Reva\RepositoryPattern\Library\Contracts\Criteria;
use Reva\RepositoryPattern\Library\Service;

class CriteriaBuilder {

    /**
     * Criteria list
     *
     * @var \Illuminate\Support\Collection
     */
    private $criteriaCollection;

    /**
     * Create builder
     *
     * @param Service|null $service
     * @param array $with
     * @return CriteriaBuilder
     */
    public static function create(Service $service = null, $with = []) {
        return new CriteriaBuilder($service, $with);
    }

    /**
     * CriteriaBuilder constructor
     *
     * @param Service $service
     * @param array $with
     */
    private function __construct(Service $service = null, $with = []) {
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