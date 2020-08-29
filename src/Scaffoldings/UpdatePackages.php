<?php

namespace Thetechyhub\Workflow\Scaffoldings;

use Illuminate\Support\Arr;

/**
 * Update the composer and npm packages in the root directory
 */
class UpdatePackages {


	/**
	 *  Run the update composer commands
	 *
	 * @return void
	 */
	public function handle() {
		$this->updateComposerPackages(false);
		$this->updateComposerPackages();

		$this->updateNpmPackages();
	}


	/**
	 *  Update the composer.json dependencies require & require-dev
	 *
	 *
	 * @param array $packages
	 * @param string $configurationKey
	 * @return array
	 */
	protected function updateComposerArray(array $packages, string $configurationKey) {

		if ($configurationKey == "require") {
			return array_merge([
				"livewire/livewire" => "^1.3",
			], $packages);
		} else {
			return array_merge([
				"codedungeon/phpunit-result-printer" => "^0.28.0",
				"laracasts/cypress" => "^1.1",
			], $packages);
		}
	}


	/**
	 * Update the "composer.json" file.
	 *
	 * @param  bool  $dev
	 * @return void
	 */
	protected function updateComposerPackages($dev = true) {
		if (!file_exists(base_path('composer.json'))) {
			return;
		}

		$configurationKey = $dev ? 'require' : 'require-dev';

		$packages = json_decode(file_get_contents(base_path('composer.json')), true);

		$packages[$configurationKey] = $this->updateComposerArray(
			array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
			$configurationKey
		);

		ksort($packages[$configurationKey]);

		file_put_contents(
			base_path('composer.json'),
			json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
		);
	}


	/**
	 *  return the new package.json dependencies
	 *
	 *
	 * @param array $packages
	 * @return array
	 */
	protected function updatePackageArray(array $packages) {
		return array_merge([
			"tailwindcss" => "^1.7.3",
			"@fortawesome/fontawesome-pro" => "^5.14.0",
			"alpinejs" => "^2.6.0",
			"cypress" => "^5.0.0",
			"babel-eslint" => "^9.0.0",
			"eslint" => "^6.8.0",
			"eslint-config-airbnb" => "^18.2.0",
			"eslint-config-prettier" => "^4.3.0",
			"eslint-config-wesbos" => "0.0.22",
			"eslint-plugin-html" => "^6.0.3",
			"eslint-plugin-import" => "^2.22.0",
			"eslint-plugin-jsx-a11y" => "^6.3.1",
			"eslint-plugin-prettier" => "^3.1.4",
			"eslint-plugin-react" => "^7.20.6",
			"eslint-plugin-react-hooks" => "^1.7.0",
			"prettier" => "^1.19.1",
			"vue-template-compiler" => "^2.6.11",
			"eslint-plugin-chai-friendly" => "^0.6.0",
			"eslint-plugin-cypress" => "^2.11.1",
		], Arr::except($packages, [
			"sass",
			"sass-loader",
			"axios"
		]));
	}


	/**
	 * Update the "package.json" file.
	 *
	 * @param  bool  $dev
	 * @return void
	 */
	protected function updateNpmPackages($dev = true) {
		if (!file_exists(base_path('package.json'))) {
			return;
		}

		$configurationKey = $dev ? 'devDependencies' : 'dependencies';

		$packages = json_decode(file_get_contents(base_path('package.json')), true);

		$packages[$configurationKey] = $this->updatePackageArray(
			array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
			$configurationKey
		);

		ksort($packages[$configurationKey]);

		file_put_contents(
			base_path('package.json'),
			json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
		);
	}
}
