<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate;

use Byume\GoogleTranslate\Traits\SupportedLanguages;
use Exception;
use Google\Cloud\Core\Exception\ServiceException;
use InvalidArgumentException;

class GoogleTranslate
{
    use SupportedLanguages;

    public function __construct(private GoogleTranslateClient $translateClient)
    {
    }

    /**
     * @throws ServiceException
     */
    public function detectLanguage(array|string $input): array
    {
        if (is_array($input)) {
            return $this->detectLanguageBatch($input);
        }

        $this->validateInput($input);

        $response = $this->translateClient->detectLanguage($input);

        return [
            'text' => $input,
            'language_code' => $response['languageCode'],
            'confidence' => $response['confidence'],
        ];
    }

    /**
     * @throws ServiceException
     */
    public function detectLanguageBatch(array $input): array
    {
        $this->validateInput($input);

        $responses = $this->translateClient->detectLanguageBatch($input);

        $translations = [];

        foreach ($responses as $response) {
            $translations[] = [
                'text' => $response['input'],
                'language_code' => $response['languageCode'],
                'confidence' => $response['confidence'],
            ];
        }

        return $translations;
    }

    /**
     * @throws Exception
     */
    public function translate(
        array|string $input,
        ?string $from = null,
        ?string $to = null,
        string $format = 'text'
    ): array {
        $this->validateInput($input);

        $translateFrom = $from ?? config('google-translate.default_source_translation');
        $translateTo = $to ?? config('google-translate.default_target_translation');

        $translateFrom = $this->sanitizeLanguageCode($translateFrom);
        $translateTo = $this->sanitizeLanguageCode($translateTo);

        if (is_array($input)) {
            return $this->translateBatch($input, $translateFrom, $translateTo, $format);
        }

        $response = $this->translateClient->translate($input, $translateFrom, $translateTo, $format);

        return [
            'source_text' => $input,
            'source_language_code' => $translateFrom,
            'translated_text' => $response['text'],
            'translated_language_code' => $translateTo,
        ];
    }

    /**
     * @throws Exception
     */
    public function justTranslate(string $input, ?string $from = null, ?string $to = null): string
    {
        $this->validateInput($input);

        $translateFrom = $from ?? config('google-translate.default_source_translation');
        $translateTo = $to ?? config('google-translate.default_target_translation');

        $translateFrom = $this->sanitizeLanguageCode($translateFrom);
        $translateTo = $this->sanitizeLanguageCode($translateTo);

        $response = $this->translateClient->translate($input, $translateFrom, $translateTo);

        return $response['text'];
    }

    /**
     * @throws Exception
     */
    public function translateBatch(
        array $input,
        string $translateFrom,
        string $translateTo,
        string $format = 'text',
    ): array {
        $translateFrom = $this->sanitizeLanguageCode($translateFrom);
        $translateTo = $this->sanitizeLanguageCode($translateTo);

        $this->validateInput($input);

        $responses = $this->translateClient->translateBatch($input, $translateFrom, $translateTo, $format);

        $translations = [];

        foreach ($responses as $response) {
            $translations[] = [
                'source_text' => $response['input'],
                'source_language_code' => $translateFrom,
                'translated_text' => $response['text'],
                'translated_language_code' => $translateTo,
            ];
        }

        return $translations;
    }

    /**
     * @throws Exception
     */
    public function getAvailableTranslationsFor(string $languageCode): array
    {
        $languageCode = $this->sanitizeLanguageCode($languageCode);

        return $this->translateClient->getAvailableTranslationsFor($languageCode);
    }

    /**
     * @throws Exception
     */
    public function translateUnlessLanguageIs(
        string $languageCode,
        string $input,
        ?string $from = null,
        ?string $to = null
    ): array {
        $translateFrom = $from ?? config('google-translate.default_source_translation');
        $translateTo = $to ?? config('google-translate.default_target_translation');

        $translateFrom = $this->sanitizeLanguageCode($translateFrom);
        $translateTo = $this->sanitizeLanguageCode($translateTo);

        $languageCode = $this->sanitizeLanguageCode($languageCode);

        $languageMisMatch = $languageCode !== $this->detectLanguage($input)['language_code'];

        if ($languageMisMatch) {
            return $this->translate($input, $translateFrom, $translateTo);
        }

        return [];
    }

    /**
     * @throws Exception
     */
    public function sanitizeLanguageCode(string $languageCode): string
    {
        $languageCode = trim(strtolower($languageCode));

        if (in_array($languageCode, $this->languages())) {
            return $languageCode;
        }

        throw new Exception(
            "Invalid or unsupported ISO 639-1 language code -{$languageCode}-,
            get the list of valid and supported language codes by running GoogleTranslate::languages()"
        );
    }

    protected function validateInput($input): void
    {
        if (is_array($input) && in_array(null, $input)) {
            throw new InvalidArgumentException('Input string cannot be null');
        }

        if (is_null($input)) {
            throw new InvalidArgumentException('Input string cannot be null');
        }
    }
}
