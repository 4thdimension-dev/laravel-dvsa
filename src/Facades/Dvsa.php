<?php

namespace FourthDimension\Dvsa\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FourthDimension\Dvsa\Dvsa
 */
class Dvsa extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \FourthDimension\Dvsa\Dvsa::class;
    }
}
