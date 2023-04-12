<?php

declare(strict_types=1);

namespace Byume\GoogleTranslate;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GoogleTranslateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/google-translate.php' => config_path('google-translate.php'),
        ]);

        $defaultLanguage = config('google-translate.default_target_translation');

        Blade::directive('translate', function ($expression) use ($defaultLanguage) {
            $expression = explode(',', $expression);

            $input = $expression[0];
            $languageCode = $expression[1] ?? $defaultLanguage;

            return "<?php echo GoogleTranslate::justTranslate($input, $languageCode); ?>";
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/google-translate.php', 'google-translate');

        $this->app->bind(GoogleTranslateClient::class, function () {
            return new GoogleTranslateClient(config('google-translate'));
        });

        $this->app->bind(GoogleTranslate::class, function () {
            $client = app(GoogleTranslateClient::class);

            return new GoogleTranslate($client);
        });

        $this->app->alias(GoogleTranslate::class, 'laravel-google-translate');
    }
}
