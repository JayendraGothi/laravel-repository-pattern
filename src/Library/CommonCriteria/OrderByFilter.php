<?php

namespace Reva\RepositoryPattern\Library\CommonCriteria;

use Illuminate\Database\Eloquent\Builder;
use Reva\RepositoryPattern\Library\Contracts\Criteria;

class OrderByFilter implements Criteria {

    /**
     * Ids For filter
     *
     * @var
     */
    private $orderBy;

    /**
     * Order of record
     *
     * @var
     */
    private $order;

    /**
     * Order by constructor.
     *
     * @param string $orderBy
     * @param string $order
     */
    public function __construct($orderBy, $order = 'ASC') {
        $this->orderBy = $orderBy;
    }

    /**
     * Apply filter for order by
     *
     * @param Builder $model
     * @return Builder
     */
    public function apply(Builder $model) {
        // get table
        $table = $model->getModel()->getTable();

        // order customer
        $model->orderBy("$table.$this->orderBy", $this->order);

        return $model;
    }
}