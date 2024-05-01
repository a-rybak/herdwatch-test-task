<?php

namespace App\Services\Requests;

class ApiRequest
{
    protected $type;

    protected $entity;

    protected $key;

    protected $limit;

    protected $options = [];

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Creates new section of options if it does not exists OR add key:value pair to specified section
     * If section was not specified adds given pair to root of options array
     * @param array $options
     * @param string $section
     */
    public function setOptions(array $options, string $section = ''): void
    {
        if (!empty($section))
            $this->options[$section] = $options;
        else
            $this->options += $options;
    }
}