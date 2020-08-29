<?php

namespace Thetechyhub\Workflow\Scaffoldings;

use Illuminate\Support\Facades\File;

/**
 * Update the formatters files for eslint and prettier
 */
class UpdateDirectories {


	/**
	 *  Run the formmatter commands
	 *
	 * @return void
	 */
	public function handle() {
		$this->clearDefaultDirection();
	}


	/**
	 *  Clean up the default directory setup of laravel resource folder
	 *
	 * @return void
	 */
	protected function clearDefaultDirection() {
		File::cleanDirectory(resource_path('js'));
		File::cleanDirectory(resource_path('views'));
		File::deleteDirectory(resource_path('sass'));
	}
}
