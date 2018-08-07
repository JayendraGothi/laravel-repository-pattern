<?php

namespace Reva\RepositoryPattern\Library;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Reva\RepositoryPattern\Library\Abstracts\CriteriaBuilder;
use Reva\RepositoryPattern\Library\Contracts\RepositoryContract;
use Reva\RepositoryPattern\Library\Exceptions\RepositoryException;

/**
 * Class RepositoryAbstract
 *
 * @package Andersonef\Repositories\Abstracts
 */
abstract class Repository implements RepositoryContract {

    /**
     * Entity
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $entity;

    /**
     * Repository constructor
     * @throws RepositoryException
     */
    function __construct() {
        $this->entity = $this->prepareEntity();
    }

    /**
     * Must return the class of entity this repository will work
     *
     * @return string
     */
    abstract function entity();

    /**
     * Return all objects from database, using the criterias on the stack.
     *
     * @param CriteriaBuilder $builder
     * @return mixed
     */
    public function all(CriteriaBuilder $builder) {
        try {
            $this->entity = $this->prepareEntity();
            $this->applyCriteria($builder);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->entity->get();
    }

    /**
     * Paginates all objects from database, using the Builder stack.
     *
     * @param CriteriaBuilder $builder
     * @param int $perpage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(CriteriaBuilder $builder, $perpage = 15) {
        try {
            $this->entity = $this->prepareEntity();
            $this->applyCriteria($builder);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->entity->paginate($perpage);
    }

    /**
     * Insert a new entity on database
     *
     * @param array $data
     * @return Model created
     */
    public function create(array $data) {
        $this->entity = app($this->entity());
        return $this->entity->create($data);
    }

    /**
     * Update entity on database.
     *
     * @param array $data
     * @param $id
     * @return
     */
    public function update(array $data, $id) {
        $this->entity = app($this->entity())->find($id);
        $this->entity->update($data);
        return app($this->entity())->find($id);
    }

    /**
     * Delete the entity on database
     *
     * @param $id
     */
    public function delete($id) {
        $this->entity = app($this->entity());
        $this->entity->destroy($id);
    }

    /**
     * Bulk delete
     *
     * @param $ids
     */
    public function deleteAll(array $ids) {
        $this->entity = app($this->entity());
        $this->entity->whereIn("id", $ids);
        $this->entity->delete();
    }

    /**
     * Find an entity using its primary key
     *
     * @param CriteriaBuilder $builder
     * @param $id
     * @return Model
     */
    public function get(CriteriaBuilder $builder, $id) {
        try {
            $this->entity = $this->prepareEntity();
            $this->applyCriteria($builder);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->entity->find($id);
    }

    /**
     * Find an entity using its primary key
     *
     * @param CriteriaBuilder $builder
     * @param $id
     * @return Model
     */
    public function first(CriteriaBuilder $builder) {
        try {
            $this->entity = $this->prepareEntity();
            $this->applyCriteria($builder);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->entity->first();
    }

    /**
     * Get the first or new
     *
     * @param array $fields
     * @return Model
     */
    public function firstOrNew(array $fields) {
        try {
            $this->entity = $this->prepareEntity();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->entity->firstOrNew($fields);
    }

    /**
     * Get the first or create
     *
     * @param array $fields
     * @return Model
     */
    public function firstOrCreate(array $fields) {
        try {
            $this->entity = $this->prepareEntity();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->entity->firstOrCreate($fields);
    }

    /**
     * Initialize the Entity.
     *
     * @throws RepositoryException
     */
    protected function prepareEntity() {
        $model = app($this->entity());
        if (!$model instanceof Model)
            throw new RepositoryException("Class {$this->entity()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        return $model->newQuery();
    }

    /**
     * Applies the criteriaCollection in the CriteriaBuilder before return the result.
     *
     * @param CriteriaBuilder $builder
     * @return $this
     */
    protected function applyCriteria(CriteriaBuilder $builder) {
        foreach ($builder->getCriterias() as $criteria) {
            $this->entity = $criteria->apply($this->entity);
        }
        return $this;
    }
}