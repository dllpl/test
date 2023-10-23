<?php

namespace extras\plugins\watermark;

use Illuminate\Support\ServiceProvider;

class WatermarkServiceProvider extends ServiceProvider
{
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('watermark', function ($app) {
			return new Watermark($app);
		});
	}
	
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Merge plugin config
        $this->mergeConfigFrom(realpath(__DIR__ . '/config.php'), 'watermark');
	
		// Load plugin languages files
		$this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'watermark');
    }
}
