<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate;

use Byume\GoogleTranslate\Traits\SupportedLanguages;
use Exception;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTranslateClient
{
    use SupportedLanguages;

    private TranslateClient $translate;

    /**
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->checkForInvalidConfiguration($config);

        $this->translate = new TranslateClient(['key' => $config['api_key']]);
    }

    /**
     * @throws ServiceException
     */
    public function detectLanguage(string $text): array
    {
        return $this->translate->detectLanguage($text);
    }

    /**
     * @throws ServiceException
     */
    public function detectLanguageBatch(array $input): array
    {
        return $this->translate->detectLanguageBatch($input);
    }

    /**
     * @throws ServiceException
     */
    public function translate(
        string $text,
        string $translateFrom,
        string $translateTo,
        string $format = 'text'
    ): ?array {
        return $this->translate->translate($text, [
            'source' => $translateFrom,
            'target' => $translateTo,
            'format' => $format,
        ]);
    }

    /**
     * @throws ServiceException
     */
    public function translateBatch(
        array $input,
        string $translateFrom,
        string $translateTo,
        string $format = 'text'
    ): array {
        return $this->translate->translateBatch($input, [
            'source' => $translateFrom,
            'target' => $translateTo,
            'format' => $format,
        ]);
    }

    /**
     * @throws ServiceException
     */
    public function getAvailableTranslationsFor(string $languageCode): array
    {
        return $this->translate->localizedLanguages(['target' => $languageCode]);
    }

    /**
     * @throws Exception
     */
    private function checkForInvalidConfiguration(array $config): void
    {
        $isTestMode = $config['test_mode_enabled'] ?? false;

        if ($isTestMode) {
            return;
        }

        $apiKey = $config['api_key'] ?? null;

        if (!$apiKey) {
            throw new Exception('Google Api Key is required.');
        }

        $codeInConfig = $config['default_target_translation'];

        $languageCodeIsValid = is_string($codeInConfig)
            && ctype_lower($codeInConfig)
            && in_array($codeInConfig, $this->languages());

        if (!$languageCodeIsValid) {
            throw new Exception(
                'The default_target_translation value in the config/google-translate.php file should
                be a valid lowercase ISO 639-1 code of the language'
            );
        }
    }
}
