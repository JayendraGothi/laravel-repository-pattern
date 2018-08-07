<?php

namespace Reva\RepositoryPattern\Library\Contracts;

use Reva\RepositoryPattern\Library\Abstracts\CriteriaBuilder;

interface ServiceContract {

    public function create(array $data);

    public function update(array $data, $id);

    public function destroy($id);

    public function addBasicFilters(CriteriaBuilder $builder);
}