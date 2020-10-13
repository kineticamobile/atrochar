<?php

namespace Kineticamobile\Atrochar\Facades;

use Illuminate\Support\Facades\Facade;

class Atrochar extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'atrochar';
    }
}
