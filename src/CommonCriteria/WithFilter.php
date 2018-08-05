<?php

namespace RepositoriesPattern\CommonCriteria;

use Illuminate\Database\Eloquent\Builder;
use RepositoriesPatter\Contracts\Criteria;

class WithFilter implements Criteria {

    /**
     * With
     *
     * @var
     */
    private $with;

    /**
     * WithFilter constructor.
     *
     * @param $with
     */
    public function __construct($with) {
        $this->with = $with;
    }

    /**
     * Apply filter for status of model
     *
     * @param Builder $model
     * @return Builder
     */
    public function apply(Builder $model) {
        $model->with($this->with);
        return $model;
    }
}