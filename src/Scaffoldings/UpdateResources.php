<?php

namespace Thetechyhub\Workflow\Scaffoldings;

use Illuminate\Support\Facades\File;

/**
 * Create or Update the resources files scaffolding.
 */
class UpdateResources {


	/**
	 *  Run the resource generation commands
	 *
	 * @return void
	 */
	public function handle() {
		$this->updateResourcesDirectory();
		$this->updateRoutes();
		$this->updateModelsDirectory();
	}


	/**
	 * Update the resources files
	 *
	 * @return void
	 */
	protected function updateResourcesDirectory() {
		File::makeDirectory(resource_path('css'));
		File::makeDirectory(resource_path('views/layouts'));
		File::makeDirectory(resource_path('views/layouts/partials'));
		File::makeDirectory(resource_path('views/common'));

		copy(
			__DIR__ . '/../stubs/css/css.stub',
			resource_path('css/app.css')
		);

		copy(
			__DIR__ . '/../stubs/js/js.stub',
			resource_path('js/app.js')
		);

		copy(
			__DIR__ . '/../stubs/views/layouts/main.stub',
			resource_path('views/layouts/main.blade.php')
		);

		copy(
			__DIR__ . '/../stubs/views/layouts/sub.stub',
			resource_path('views/layouts/sub.blade.php')
		);

		copy(
			__DIR__ . '/../stubs/views/layouts/partials/header.stub',
			resource_path('views/layouts/partials/header.blade.php')
		);

		copy(
			__DIR__ . '/../stubs/views/layouts/partials/footer.stub',
			resource_path('views/layouts/partials/footer.blade.php')
		);

		copy(
			__DIR__ . '/../stubs/views/common/welcome.stub',
			resource_path('views/common/welcome.blade.php')
		);
	}


	protected function updateRoutes() {
		copy(
			__DIR__ . '/../stubs/routes/web.stub',
			base_path('routes/web.php')
		);
	}


	protected function updateModelsDirectory() {
		File::makeDirectory(app_path('Models'));

		copy(
			app_path('User.php'),
			app_path('Models/User.php')
		);

		File::delete(app_path('User.php'));
	}
}
