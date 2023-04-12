<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate\Tests\GoogleTranslate;

use Byume\GoogleTranslate\Tests\BaseGoogleTranslateTest;
use Exception;

class DetectLanguageTest extends BaseGoogleTranslateTest
{
    /**
     * @throws Exception
     */
    public function testDetectLanguageWithStringInput(): void
    {
        $this->translateClient
            ->shouldReceive('detectLanguage')->with($this->testString)
            ->once()
            ->andReturn(['languageCode' => 'en', 'confidence' => '']);

        $response = $this->translate->detectLanguage($this->testString);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('language_code', $response);
        $this->assertArrayHasKey('confidence', $response);
    }

    /**
     * @throws Exception
     */
    public function testDetectLanguageWithStringsArrayInput(): void
    {
        $this->translateClient
            ->shouldReceive('detectLanguageBatch')->with([$this->testString, $this->testString])
            ->once()
            ->andReturn([
                ['languageCode' => 'en', 'confidence' => '', 'input' => $this->testString],
                ['languageCode' => 'en', 'confidence' => '', 'input' => $this->testString],
            ]);

        $response = $this->translate->detectLanguage([$this->testString, $this->testString]);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('text', $response[0]);
        $this->assertArrayHasKey('language_code', $response[0]);
        $this->assertArrayHasKey('confidence', $response[0]);
        $this->assertArrayHasKey('text', $response[1]);
        $this->assertArrayHasKey('language_code', $response[1]);
        $this->assertArrayHasKey('confidence', $response[1]);
    }
}
