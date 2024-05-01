<?php

namespace App\Services\Builders;

use App\Helpers\ApiCommandHelper;

class PutRequestBuilder extends PostRequestBuilder
{
    public function buildType()
    {
        $this->request->setType(ApiCommandHelper::PUT_REQUEST);
    }

    public function buildPrimaryKey(int $key)
    {
        $this->request->setKey($key);
    }
}