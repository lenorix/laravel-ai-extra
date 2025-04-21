<?php

namespace Lenorix\LaravelAiExtra\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lenorix\LaravelAiExtra\LaravelAiExtra
 */
class LaravelAiExtra extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Lenorix\LaravelAiExtra\LaravelAiExtra::class;
    }
}
