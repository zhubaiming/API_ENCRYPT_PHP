<?php

namespace Hongyi\ApiEncrypt\Facades;

use Illuminate\Support\Facades\Facade;

class ApiEncrypt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'apiEncrypt';
    }
}