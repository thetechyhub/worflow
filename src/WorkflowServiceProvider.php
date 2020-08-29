<?php

namespace Thetechyhub\Workflow;

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

			$this->commands([
				SetupCommand::class
			]);
		}
	}

	/**
	 * Register the application services.
	 */
	public function register() {
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'workflow');

		$this->app->singleton('workflow', function () {
			return new Workflow;
		});
	}
}
