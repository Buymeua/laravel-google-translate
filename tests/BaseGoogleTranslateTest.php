<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate\Tests;

use Byume\GoogleTranslate\GoogleTranslate;
use Byume\GoogleTranslate\GoogleTranslateClient;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Mockery;

abstract class BaseGoogleTranslateTest extends BaseTestCase
{
    public string $testString = 'A test string';

    public string $testHtmlString = '<p>A test string</p>';

    protected MockInterface $translateClient;

    protected GoogleTranslate $translate;

    public function __construct()
    {
        parent::__construct();

        $this->translateClient = Mockery::mock(GoogleTranslateClient::class);

        $this->translate = new GoogleTranslate($this->translateClient);
    }
}
