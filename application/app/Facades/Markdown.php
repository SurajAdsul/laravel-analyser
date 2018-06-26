<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Markdown extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Analyser\Markdown::class;
    }
}
