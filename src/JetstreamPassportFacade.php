<?php

namespace HeaderX\JetstreamPassport;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HeaderX\JetstreamPassport\JetstreamPassport
 */
class JetstreamPassportFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-jetstream-passport';
    }
}
