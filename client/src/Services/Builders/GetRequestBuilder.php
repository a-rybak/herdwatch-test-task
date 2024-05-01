<?php

namespace App\Services\Builders;

use App\Helpers\ApiCommandHelper;

class GetRequestBuilder extends AbstractRequestBuilder
{
    public string $limitOperandName = '_limit';

    public function buildType()
    {
        $this->request->setType(ApiCommandHelper::GET_REQUEST);
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
        $this->request->setLimit($limit);
        $this->request->setOptions(options: [$this->limitOperandName => $limit], section: 'query');
    }
}