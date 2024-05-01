<?php

namespace App\Services\Builders;

use App\Helpers\ApiCommandHelper;

class PatchRequestBuilder extends AbstractRequestBuilder
{
    public function buildType()
    {
        $this->request->setType(ApiCommandHelper::PATCH_REQUEST);
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

    public function buildBody(array $data)
    {
        $this->request->setOptions(options: $data, section: 'body');
    }
}