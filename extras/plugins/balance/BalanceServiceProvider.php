<?php

namespace extras\plugins\balance;

use Illuminate\Support\ServiceProvider;

class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('balance', function ($app) {
            return new Balance($app);
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Загрузка представлений плагина
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'payment');

        // Загрузка языковых файлов плагина
        $this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'balance');

        // Объединение конфигурации плагина
        $this->mergeConfigFrom(realpath(__DIR__ . '/config.php'), 'payment');

        // Если необходимо, добавьте дополнительные действия, например, маршруты
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
