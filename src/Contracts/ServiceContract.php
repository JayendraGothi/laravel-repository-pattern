<?php

namespace RepositoriesPattern\Contracts;


use RepositoriesPattern\Abstracts\CriteriaBuilder;

interface ServiceContract {

    public function create(array $data);

    public function update(array $data, $id);

    public function destroy($id);

    public function addBasicFilters(CriteriaBuilder $builder);
}