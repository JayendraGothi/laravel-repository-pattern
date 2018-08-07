<?php

namespace Reva\RepositoryPattern\Library\CommonCriteria;

use Illuminate\Database\Eloquent\Builder;
use Reva\RepositoryPattern\Library\Contracts\Criteria;

class SelectFilter implements Criteria {

    private $selectRaw;

    /**
     * Constructor
     *
     * @param $selectRaw
     */
    public function __construct($selectRaw = null) {
        $this->selectRaw = $selectRaw;
    }

    /**
     * Add select statement
     *
     * @param Builder $model
     * @return Builder
     */
    public function apply(Builder $model) {
        if (!isset($this->selectRaw))
            $model->selectRaw($model->getModel()->getTable() . '.*');
        else
            $model->selectRaw($this->selectRaw);
        return $model;
    }
}