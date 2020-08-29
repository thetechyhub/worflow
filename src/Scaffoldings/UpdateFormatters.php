<?php

namespace Thetechyhub\Workflow\Scaffoldings;

/**
 * Update the formatters files for eslint and prettier
 */
class UpdateFormatters {


	/**
	 *  Run the formmatter commands
	 *
	 * @return void
	 */
	public function handle() {
		$this->updateFormatterConfig();
	}


	/**
	 * Update the all necessary files for formatting
	 *
	 * @return void
	 */
	protected function updateFormatterConfig() {
		copy(
			__DIR__ . '/../stubs/config/formatter/eslintrc.stub',
			base_path('.eslintrc')
		);

		copy(
			__DIR__ . '/../stubs/config/formatter/eslintignore.stub',
			base_path('.eslintignore')
		);


		copy(
			__DIR__ . '/../stubs/config/formatter/prettierignore.stub',
			base_path('.prettierignore')
		);

		copy(
			__DIR__ . '/../stubs/config/formatter/prettierrc.stub',
			base_path('.prettierrc')
		);


		copy(
			__DIR__ . '/../stubs/config/formatter/editorconfig.stub',
			base_path('.editorconfig')
		);
	}
}
