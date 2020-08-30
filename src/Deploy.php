<?php

namespace Thetechyhub\Workflow;

use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Symfony\Component\Process\Process;

class Deploy {

	/** branch */
	public $branch;

	/** server options */
	public const SERVER_STAGING = 'staging';
	public const SERVER_LIVE = 'live';


	public function handle(string $target) {
		if ($target == static::SERVER_LIVE) {
			$this->branch = config('workflow.branches.release');
		} elseif ($target == static::SERVER_STAGING) {
			$this->branch = config('workflow.branches.staging');
		} else {
			throw new InvalidArgumentException("Unsupported target option.");
		}

		$this->deploy();
	}

	public function deploy() {
		Artisan::call('down', [
			'--message' => "We are working on improving your experience, this should not take long.",
		]);

		$process = new Process(['git', 'fetch', 'origin', $this->branch]);
		$process->run();

		$process = new Process(['git', 'reset', '--hard', "origin/{$this->branch}"]);
		$process->run();

		$process = new Process(['composer', 'install', '--no-interaction', '--prefer-dist', '--optimize-loader']);
		$process->run();


		Artisan::call('migrate --force');

		Artisan::call('optimize');

		Artisan::call('up');
	}
}
