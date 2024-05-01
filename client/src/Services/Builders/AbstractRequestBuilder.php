<?php

namespace App\Services\Builders;

use App\Services\Requests\ApiRequest;

abstract class AbstractRequestBuilder
{
    protected ApiRequest $request;

    public function __construct()
    {
        $this->request = new ApiRequest();

        $this->buildType();
        $this->setDefaultOptions();
    }

    public function getRequest(): ApiRequest
    {
        return $this->request;
    }

    protected function setDefaultOptions()
    {
        $this->request->setOptions([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'query' => [],
            'body' => [],
        ]);
    }

    abstract public function buildType();

    abstract public function buildEntity(string $entity);

    abstract public function buildPrimaryKey(int $key);

    abstract public function buildLimit(int $limit);

}