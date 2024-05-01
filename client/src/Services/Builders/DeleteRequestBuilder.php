<?php

namespace App\Services\Builders;

use App\Helpers\ApiCommandHelper;

class DeleteRequestBuilder extends AbstractRequestBuilder
{

    public function buildType()
    {
        $this->request->setType(ApiCommandHelper::DELETE_REQUEST);
    }

    public function buildEntity(string $entity)
    {
        $this->request->setEntity($entity);
    }

    public function buildPrimaryKey(int $key)
    {
        $this->request->setKey($key);
    }

    public function buildLimit(int $limit)
    {
    }
}