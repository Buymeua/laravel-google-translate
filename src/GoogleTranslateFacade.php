<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate;

use Illuminate\Support\Facades\Facade;

/**
 * @see GoogleTranslate
 */
class GoogleTranslateFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-google-translate';
    }
}
