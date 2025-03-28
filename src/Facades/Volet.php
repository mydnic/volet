<?php

namespace Mydnic\Volet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mydnic\Volet\Volet
 */
class Volet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mydnic\Volet\Volet::class;
    }
}
