<?php

namespace Thetechyhub\Workflow\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Thetechyhub\Workflow\Exceptions\SetupFailedException;

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
		$branchs = config('workflow.branches');
		$developmentBranch = config('workflow.branches.development');

		$this->verifyCurrentBranch($developmentBranch);


		foreach ($branchs as $stage => $branch) {
			if ($stage === 'development') {
				continue;
			}

			$process = Process::fromShellCommandline("git checkout $branch 2>/dev/null || git checkout -b $branch");
			$process->run();

			$this->info("Created Branch {$branch}");


			$process = new Process(['git', 'checkout', $developmentBranch]);
			$process->run();
		}

		$this->info("Setup Complete!");
	}

	private function verifyCurrentBranch($developmentBranch) {
		$branch = null;

		$process = new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
		$process->run();

		if (!$process->isSuccessful()) {
			$error = trim($process->getErrorOutput());

			if (strpos($error, "not a git repository")) {
				$this->initRepo($developmentBranch);
				return 0;
			} else {
				throw new	SetupFailedException($process->getErrorOutput());
			}
		}

		$branch = trim($process->getOutput());

		if ($branch != $developmentBranch) {
			throw new	SetupFailedException("You must be in {$developmentBranch} branch to run this command.");
		}
	}

	private function initRepo($branch) {

		$process = new Process(['git', 'init']);
		$process->run();

		$this->info($process->getOutput());

		$process = new Process(['git', 'checkout', '-b', $branch]);
		$process->run();

		$process = new Process(['git', 'add', '.']);
		$process->run();

		$process = new Process(['git', 'commit', '-m', 'Workflow Setup Complete']);
		$process->run();

		$this->info($process->getOutput());


		return $this->verifyCurrentBranch($branch);
	}
}
