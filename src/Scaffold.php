<?php

namespace Thetechyhub\Workflow;

class Scaffold {

	/**
	 * Tasks to run to generate the scaffold boilerplate
	 *
	 */
	protected const TASKS = [
		\Thetechyhub\Workflow\Scaffoldings\UpdateDirectories::class,
		\Thetechyhub\Workflow\Scaffoldings\UpdateConfigs::class,
		\Thetechyhub\Workflow\Scaffoldings\UpdateFormatters::class,
		\Thetechyhub\Workflow\Scaffoldings\UpdatePackages::class,
		\Thetechyhub\Workflow\Scaffoldings\UpdateResources::class,
	];


	/**
	 * Install the scaffold.
	 *
	 * @return void
	 */
	public static function install() {

		foreach (static::TASKS as $task) {
			(new $task)->handle();
		}
	}
}
