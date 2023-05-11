<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Ethiopian extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ethiopian';
    }
}
