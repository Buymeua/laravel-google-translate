<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate\Tests\GoogleTranslate;

use Byume\GoogleTranslate\Tests\BaseGoogleTranslateTest;
use Exception;

class TranslateTest extends BaseGoogleTranslateTest
{
    /**
     * @throws Exception
     */
    public function testTranslateWithStringInput(): void
    {
        $this->translateClient
            ->shouldReceive('translate')->with($this->testString, 'en', 'hi', 'text')
            ->once()
            ->andReturn(['source' => 'en', 'text' => '']);

        $response = $this->translate->translate($this->testString, 'en', 'hi');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response);
        $this->assertArrayHasKey('source_language_code', $response);
        $this->assertArrayHasKey('translated_text', $response);
        $this->assertArrayHasKey('translated_language_code', $response);
    }

    /**
     * @throws Exception
     */
    public function testTranslateWithHtmlStringInput(): void
    {
        $this->translateClient
            ->shouldReceive('translate')->with($this->testHtmlString, 'en', 'hi', 'html')
            ->once()
            ->andReturn(['source' => 'en', 'text' => '']);

        $response = $this->translate->translate($this->testHtmlString, 'en', 'hi', 'html');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response);
        $this->assertArrayHasKey('source_language_code', $response);
        $this->assertArrayHasKey('translated_text', $response);
        $this->assertArrayHasKey('translated_language_code', $response);
    }

    /**
     * @throws Exception
     */
    public function testTranslateWithStringsArrayInput(): void
    {
        $this->translateClient
            ->shouldReceive('translateBatch')->with([$this->testString, $this->testString], 'en', 'hi', 'text')
            ->once()
            ->andReturn([
                ['source' => 'en', 'text' => '', 'input' => $this->testString],
                ['source' => 'en', 'text' => '', 'input' => $this->testString],
            ]);

        $response = $this->translate->translate([$this->testString, $this->testString], 'en', 'hi');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response[0]);
        $this->assertArrayHasKey('source_language_code', $response[0]);
        $this->assertArrayHasKey('translated_text', $response[0]);
        $this->assertArrayHasKey('translated_language_code', $response[0]);
        $this->assertArrayHasKey('source_text', $response[1]);
        $this->assertArrayHasKey('source_language_code', $response[1]);
        $this->assertArrayHasKey('translated_text', $response[1]);
        $this->assertArrayHasKey('translated_language_code', $response[1]);
    }

    /**
     * @throws Exception
     */
    public function testJustTranslateWithStringInput(): void
    {
        $this->translateClient
            ->shouldReceive('translate')->with($this->testString, 'en', 'hi')
            ->once()
            ->andReturn(['text' => 'A test string']);

        $response = $this->translate->justTranslate($this->testString, 'en', 'hi');

        $this->assertEquals('A test string', $response);
    }
}
