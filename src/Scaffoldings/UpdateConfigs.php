<?php

namespace Thetechyhub\Workflow\Scaffoldings;

/**
 * Update the formatters files for eslint and prettier
 */
class UpdateConfigs {


	/**
	 *  Run the update composer commands
	 *
	 * @return void
	 */
	public function handle() {
		$this->updateFontAwesomeConfiguration();
		$this->updateTailwindConfiguration();
		$this->updateWebpackConfiguration();
		$this->updatePhpunitConfiguration();
	}



	/**
	 * Update the .npmrc required by fontawesom pro
	 *
	 * @return void
	 */
	protected function updateFontAwesomeConfiguration() {
		copy(
			__DIR__ . '/../stubs/config/npmrc.stub',
			base_path('.npmrc')
		);
	}

	/**
	 * Update the tailwindcss configuration file
	 *
	 * @return void
	 */
	protected function updateTailwindConfiguration() {
		copy(
			__DIR__ . '/../stubs/config/tailwind.stub',
			base_path('tailwind.config.js')
		);
	}


	/**
	 * Update the webpack.mix.js configuration file
	 *
	 * @return void
	 */
	protected function updateWebpackConfiguration() {
		copy(
			__DIR__ . '/../stubs/config/webpack.mix.stub',
			base_path('webpack.mix.js')
		);
	}

	/**
	 * Update the phpunit.xml configuration file
	 *
	 * @return void
	 */
	protected function updatePhpunitConfiguration() {
		copy(
			__DIR__ . '/../stubs/config/phpunit.stub',
			base_path('phpunit.xml')
		);
	}
}
