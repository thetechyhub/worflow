<?php

namespace Thetechyhub\Workflow;

use Symfony\Component\Process\Process;
use Thetechyhub\Workflow\Exceptions\VersionControlInstallException;

class VersionControle {


	/**
	 *  install the version control setup
	 *
	 *
	 * @return void
	 */
	public static function install() {
		$branchs = config('workflow.branches');
		$developmentBranch = config('workflow.branches.development');

		static::verifyBranch($developmentBranch);
		static::generate($branchs, $developmentBranch);
	}


	/**
	 *  generate the git branches required for the CI/CD workflow
	 *
	 *
	 * @return void
	 */
	protected static function generate($branchs, $developmentBranch) {
		foreach ($branchs as $stage => $branch) {
			if ($stage === 'development') {
				continue;
			}

			$process = Process::fromShellCommandline("git checkout $branch 2>/dev/null || git checkout -b $branch");
			$process->run();

			$process = new Process(['git', 'checkout', $developmentBranch]);
			$process->run();
		}
	}


	/**
	 *  Verify that the current branch is the development branch
	 *
	 *
	 * @throws VersionControlInstallException
	 * @return void
	 */
	protected static function verifyBranch($developmentBranch) {
		$branch = null;

		$process = new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
		$process->run();

		if (!$process->isSuccessful()) {
			$error = trim($process->getErrorOutput());

			if (strpos($error, "not a git repository")) {
				static::initRepo($developmentBranch);
				return;
			} else {
				throw new	VersionControlInstallException($process->getErrorOutput());
			}
		}

		$branch = trim($process->getOutput());

		if ($branch != $developmentBranch) {
			throw new	VersionControlInstallException("You must be in {$developmentBranch} branch to run this command.");
		}
	}

	protected static function initRepo($branch) {

		$process = new Process(['git', 'init']);
		$process->run();

		$process = new Process(['git', 'checkout', '-b', $branch]);
		$process->run();

		$process = new Process(['git', 'add', '.']);
		$process->run();

		$process = new Process(['git', 'commit', '-m', 'Workflow Setup Complete']);
		$process->run();

		static::verifyBranch($branch);
	}
}
