<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate\Facades;

use Byume\GoogleTranslate\GoogleTranslate as BaseGoogleTranslate;
use Illuminate\Support\Facades\Facade;

/**
 * @see BaseGoogleTranslate
 */
class GoogleTranslate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-google-translate';
    }
}
