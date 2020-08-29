<?php

namespace Thetechyhub\Workflow;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Laravel\Ui\Presets\Preset as UiPreset;

class Preset extends UiPreset {

	public static function install() {
		static::clearDefaultDirection();
		static::updatePackages();
		static::updateComposerPackages(false);
		static::updateComposerPackages();
		static::buildResourcesDirectory();
		static::setupFontAwesome();
		static::setupTailwindConfiguration();
		static::updateWebpackConfiguration();
		static::updateRoutes();
		static::createFormatterConfig();
		static::updateModelsDirectory();
	}

	public static function clearDefaultDirection() {
		File::cleanDirectory(resource_path('js'));
		File::cleanDirectory(resource_path('views'));
		File::deleteDirectory(resource_path('sass'));
	}

	public static function buildResourcesDirectory() {
		File::makeDirectory(resource_path('css'));
		File::makeDirectory(resource_path('views/layouts'));
		File::makeDirectory(resource_path('views/layouts/partials'));
		File::makeDirectory(resource_path('views/common'));

		copy(
			__DIR__ . '/stubs/css/css.stub',
			resource_path('css/app.css')
		);

		copy(
			__DIR__ . '/stubs/js/js.stub',
			resource_path('js/app.js')
		);

		copy(
			__DIR__ . '/stubs/views/layouts/main.stub',
			resource_path('views/layouts/main.blade.php')
		);

		copy(
			__DIR__ . '/stubs/views/layouts/sub.stub',
			resource_path('views/layouts/sub.blade.php')
		);

		copy(
			__DIR__ . '/stubs/views/layouts/partials/header.stub',
			resource_path('views/layouts/partials/header.blade.php')
		);

		copy(
			__DIR__ . '/stubs/views/layouts/partials/footer.stub',
			resource_path('views/layouts/partials/footer.blade.php')
		);

		copy(
			__DIR__ . '/stubs/views/common/welcome.stub',
			resource_path('views/common/welcome.blade.php')
		);
	}

	public static function updatePackageArray(array $packages) {
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

	public static function updateComposerArray(array $packages, $configurationKey) {

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
	protected static function updateComposerPackages($dev = true) {
		if (!file_exists(base_path('composer.json'))) {
			return;
		}

		$configurationKey = $dev ? 'require' : 'require-dev';

		$packages = json_decode(file_get_contents(base_path('composer.json')), true);

		$packages[$configurationKey] = static::updateComposerArray(
			array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
			$configurationKey
		);

		ksort($packages[$configurationKey]);

		file_put_contents(
			base_path('composer.json'),
			json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
		);
	}

	public static function setupFontAwesome() {
		copy(
			__DIR__ . '/stubs/config/npmrc.stub',
			base_path('.npmrc')
		);
	}

	public static function setupTailwindConfiguration() {
		copy(
			__DIR__ . '/stubs/config/tailwind.stub',
			base_path('tailwind.config.js')
		);
	}

	protected static function updateWebpackConfiguration() {
		copy(
			__DIR__ . '/stubs/config/webpack.mix.stub',
			base_path('webpack.mix.js')
		);
	}

	protected static function updateRoutes() {
		copy(
			__DIR__ . '/stubs/routes/web.stub',
			base_path('routes/web.php')
		);
	}

	protected static function createFormatterConfig() {
		copy(
			__DIR__ . '/stubs/config/phpunit.stub',
			base_path('phpunit.xml')
		);

		copy(
			__DIR__ . '/stubs/config/formatter/eslintrc.stub',
			base_path('.eslintrc')
		);

		copy(
			__DIR__ . '/stubs/config/formatter/eslintignore.stub',
			base_path('.eslintignore')
		);


		copy(
			__DIR__ . '/stubs/config/formatter/prettierignore.stub',
			base_path('.prettierignore')
		);

		copy(
			__DIR__ . '/stubs/config/formatter/prettierrc.stub',
			base_path('.prettierrc')
		);


		copy(
			__DIR__ . '/stubs/config/formatter/editorconfig.stub',
			base_path('.editorconfig')
		);
	}

	protected static function updateModelsDirectory() {
		File::makeDirectory(app_path('Models'));

		copy(
			app_path('User.php'),
			app_path('Models/User.php')
		);

		File::delete(app_path('User.php'));
	}
}
