<?php

namespace Thetechyhub\Workflow\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Thetechyhub\Workflow\Exceptions\SetupFailedException;
use Thetechyhub\Workflow\VersionControle;

class SetupCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'workflow:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup git branchs following the guide lines.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$this->handleVersionControle();
	}

	protected function handleVersionControle() {
		VersionControle::install();

		$this->info('Git version controll scaffolding is complete.');
		$this->comment('You can run "git remote add origin REPO_URL" to set your remote repository.');
	}
}
