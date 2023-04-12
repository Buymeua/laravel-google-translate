<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate\Tests\GoogleTranslate;

use Byume\GoogleTranslate\Tests\BaseGoogleTranslateTest;
use Exception;

class TranslateUnlessLanguageIsTest extends BaseGoogleTranslateTest
{
    /**
     * @throws Exception
     */
    public function testTranslateUnlessLanguageIsDoesntTranslateIfInputLanguageSameAsInMethod()
    {
        $this->translateClient
            ->shouldReceive('detectLanguage')->with($this->testString)
            ->once()
            ->andReturn(['languageCode' => 'en', 'confidence' => '']);

        $response = $this->translate->translateUnlessLanguageIs('en', $this->testString, 'hi', 'en');

        $this->assertEmpty($response);
    }

    /**
     * @throws Exception
     */
    public function testTranslateUnlessLanguageIsTranslateIfInputLanguageSameAsInMethod()
    {
        $this->translateClient
            ->shouldReceive('detectLanguage')->with($this->testString)
            ->once()
            ->andReturn(['languageCode' => 'en', 'confidence' => '']);

        $this->translateClient
            ->shouldReceive('translate')->with($this->testString, 'hi', 'en', 'text')
            ->once()
            ->andReturn(['source' => 'en', 'text' => '']);

        $response = $this->translate->translateUnlessLanguageIs('hi', $this->testString, 'hi', 'en');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response);
        $this->assertArrayHasKey('source_language_code', $response);
        $this->assertArrayHasKey('translated_text', $response);
        $this->assertArrayHasKey('translated_language_code', $response);
    }
}
