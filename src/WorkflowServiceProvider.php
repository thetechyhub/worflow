<?php

namespace Thetechyhub\Workflow;

use Illuminate\Support\ServiceProvider;
use Thetechyhub\Workflow\Commands\ControllerMakeCommand;
use Thetechyhub\Workflow\Commands\DeployCommand;
use Thetechyhub\Workflow\Commands\ModelMakeCommand;
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
				SetupCommand::class,
				DeployCommand::class,
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

		$this->extendCommands();
	}


	/**
	 * Extend Laravel Console Commands.
	 *
	 * @return void
	 */
	public function extendCommands() {
		$this->app->extend('command.model.make', function ($command, $app) {
			return new ModelMakeCommand($app['files']);
		});

		$this->app->extend('command.controller.make', function ($command, $app) {
			return new ControllerMakeCommand($app['files']);
		});
	}
}
