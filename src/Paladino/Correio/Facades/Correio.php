<?php

namespace Paladino\Correio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * PagSeguro Laravel Facade
 * @author  Michael Douglas <michaeldouglas010790@gmail.com>
 */
class Correio extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'correio';
    }
}
