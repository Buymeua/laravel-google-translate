# Laravel package for the Google Translate API

This package makes using the Google Translate API in your laravel app a breeze with minimum to no configuration, clean syntax and a consistent package API.

## Installation

- You can install this package via composer using this command:

```bash
composer require buyme/laravel-google-translate
```

- The package will automatically register itself.

- You can publish the config file using the following command:

```bash
php artisan vendor:publish --provider="Byume\GoogleTranslate\GoogleTranslateServiceProvider"
```

This will create the package's config file called `google-translate.php` in the `config` directory. These are the contents of the published config file:

```php
return [
    /*
    |----------------------------------------------------------------------------------------------------
    | The ISO 639-1 code of the language in lowercase to which the text will be translated to by default.
    |----------------------------------------------------------------------------------------------------
    */
    'default_target_translation' => 'en',

    /*
    |-------------------------------------------------------------------------------
    | Path to the json file containing the authentication credentials.
    |
    |-------------------------------------------------------------------------------
    */
    'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
];
```

## How to use

- After setting up the config file values you are all set to use the following methods :smile:

- Detecting the language. You can pass both, a single string or an array of multiple strings to it:

```php
GoogleTranslate::detectLanguage('Hello world'): array

GoogleTranslate::detectLanguage(['Hello world', 'Laravel is the best']);

// Returns multi-dimensional array containing result set for all the array elements.
```

- Translating the string(s): The `translate` method accepts a third optional argument which can be the code of the language you want the string to be translated in. You can specify the default option in the config file:

```php
// GoogleTranslate::translate($input, $from = null, $to = null, $format = 'text'): array

GoogleTranslate::translate('Hello world'): array

GoogleTranslate::translate(['Hello world', 'Laravel is the best']);

// Returns multi-dimensional array containing result set for all the array elements.
```

- Get all the available translations from 'Google Translation' for a particular language by passing its language code:

```php
GoogleTranslate::getAvailableTranslationsFor('en'): array
```

- Translate unless the language is same as the first argument. This method is a clean way to ask the package to detect the language of the given string, if it is same as the first argument, translation isn't performed. It accepts an optional third argument which is the language code you want the string to be translated in. You can specify the default option in the config file. If the languages are same, the input string is returned as it is, else an array is returned containing the translation results:

```php
GoogleTranslate::translateUnlessLanguageIs('en', string $text);
```

- Translating and just returning back the translated string. It accepts an optional second argument which is the language code you want the string to be translated in. You can specify the default option in the config file.

```php
GoogleTranslate::justTranslate(string $text): string
```

- There is is an optional third parameter for format to take advantage for better html translation support. Google Translate API currently supports 'text' and 'html' as parameters. The default for this parameter is 'text' as it has the best use case for most translations.
  [Google Translate API Docs](https://cloud.google.com/translate/docs/reference/rest/v2/translate)

```php
GoogleTranslate::translateUnlessLanguageIs('en', string $text, string $format);
```

- There is also a nice blade helper called `@translate` that comes with the package to make its use more neat in the view files. It accepts an optional second argument which is the language code you want the string to be translated in. You can specify the default option in the config file.

```
@translate('Hello World')
```

## Testing

You can run the tests with:

```bash
vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see the [License File](LICENSE.txt) for more information.
