<?php

namespace Thetechyhub\Workflow;

use Laravel\Ui\UiCommand;
use Illuminate\Support\ServiceProvider;
use Thetechyhub\Workflow\Commands\SetupCommand;

class WorkflowServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 */
	public function boot() {

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('workflow.php'),
			], 'config');

			// Registering package commands.
			$this->commands([
				SetupCommand::class
			]);
		}
	}

	/**
	 * Register the application services.
	 */
	public function register() {
		// Automatically apply the package configuration
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'workflow');


		UiCommand::macro('tailwind', function (UiCommand $command) {
			Preset::install();
		});
	}
}
