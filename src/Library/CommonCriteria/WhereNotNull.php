<?php

namespace Reva\RepositoryPattern\Library\CommonCriteria;

use Illuminate\Database\Eloquent\Builder;
use Reva\RepositoryPattern\Library\Contracts\Criteria;

class WhereNotNull implements Criteria {

    /**
     * field
     *
     * @var
     */
    private $field;

    /**
     * Constructor.
     *
     * @param $field
     */
    public function __construct($field) {
        $this->field = $field;
    }

    /**
     * Apply filter for status
     *
     * @param Builder $model
     * @return Builder
     */
    public function apply(Builder $model) {
        // get table
        $table = $model->getModel()->getTable();

        // filter for status
        $model->whereNotNull("$table.$this->field");

        return $model;
    }
}