<?php

namespace Thetechyhub\Workflow;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 */
	public function boot() {
		/*
         * Optional methods to load your package assets
         */
		// $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'workflow');
		// $this->loadViewsFrom(__DIR__.'/../resources/views', 'workflow');
		// $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
		// $this->loadRoutesFrom(__DIR__.'/routes.php');

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('workflow.php'),
			], 'config');

			// Publishing the views.
			/*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/workflow'),
            ], 'views');*/

			// Publishing assets.
			/*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/workflow'),
            ], 'assets');*/

			// Publishing the translation files.
			/*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/workflow'),
            ], 'lang');*/

			// Registering package commands.
			// $this->commands([]);
		}
	}

	/**
	 * Register the application services.
	 */
	public function register() {
		// Automatically apply the package configuration
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'workflow');

		// Register the main class to use with the facade
		$this->app->singleton('workflow', function () {
			return new Workflow;
		});
	}
}
