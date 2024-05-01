<?php

namespace App\Helpers;

class ApiCommandHelper
{
    CONST GROUP_ENTITY = 'groups';
    CONST USER_ENTITY  = 'users';
    CONST POST_ENTITY  = 'posts';

    CONST GET_REQUEST    = 'GET';
    CONST POST_REQUEST   = 'POST';
    CONST PUT_REQUEST    = 'PUT';
    CONST PATCH_REQUEST  = 'PATCH';
    CONST DELETE_REQUEST = 'DELETE';

    public static function getEntities(): array
    {
        return [
            self::GROUP_ENTITY,
            self::USER_ENTITY,
            self::POST_ENTITY
        ];
    }

    public static function mapEntityFields($entity)
    {
        return match ($entity) {
            self::GROUP_ENTITY => ['name'],
            self::USER_ENTITY  => ['name', 'email'],
            self::POST_ENTITY  => ['title', 'body', 'userId'],
        };
    }
}