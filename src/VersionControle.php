<?php

namespace Thetechyhub\Workflow;

use Symfony\Component\Process\Process;
use Thetechyhub\Workflow\Exceptions\VersionControlInstallException;

class VersionControle {


	/** development branch */
	public $development;


	/** staging branch */
	public $staging;


	/** release branch */
	public $release;


	/** List of branches */
	protected $branches;


	/** Release Type */
	public const RELEASE_MAJOR = 'Major';
	public const RELEASE_MINOR = 'Minor';
	public const RELEASE_PATCH = 'Patch';

	/** Beta release label */
	public const BETA_LABEL = '-beta';


	public function __construct() {
		$this->branches = config('workflow.branches');

		$this->development = config('workflow.branches.development');
		$this->staging = config('workflow.branches.staging');
		$this->release = config('workflow.branches.release');
	}


	/**
	 *  install the version control setup
	 *
	 *
	 * @return void
	 */
	public function install() {
		$this->verifyBranch();
		$this->generate();
	}


	/**
	 *  generate the git branches required for the CI/CD workflow
	 *
	 *
	 * @return void
	 */
	protected function generate() {
		foreach ($this->branchs as $stage => $branch) {
			if ($branch === $this->development) {
				continue;
			}

			$process = Process::fromShellCommandline("git checkout $branch 2>/dev/null || git checkout -b $branch");
			$process->run();

			$process = new Process(['git', 'checkout', $this->development]);
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
	public function verifyBranch() {
		$branch = null;

		$process = new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
		$process->run();

		if (!$process->isSuccessful()) {
			$error = trim($process->getErrorOutput());

			if (strpos($error, "not a git repository")) {
				$this->initRepo();
				return;
			} else {
				throw new	VersionControlInstallException($process->getErrorOutput());
			}
		}

		$branch = trim($process->getOutput());

		if ($branch != $this->development) {
			throw new	VersionControlInstallException("You must be in {$this->development} branch to run this command.");
		}
	}

	/**
	 *  Init the git repository
	 *
	 * @return void
	 */
	protected  function initRepo() {

		$process = new Process(['git', 'init']);
		$process->run();

		$process = new Process(['git', 'checkout', '-b', $this->development]);
		$process->run();

		$process = new Process(['git', 'add', '.']);
		$process->run();

		$process = new Process(['git', 'commit', '-m', 'Workflow Setup Complete']);
		$process->run();

		$this->verifyBranch();
	}


	/**
	 * Run deploy
	 *
	 * @param string $target
	 * @param string $type
	 * @return void
	 */
	public function deploy(string $target, string $type) {
		$this->verifyBranch();


		$process = new Process(['git', 'pull', '--tags', 'origin', $this->development]);
		$process->run();


		$process = new Process(['git', 'checkout', $target]);
		$process->run();

		$process = new Process(['git', 'merge', $this->development]);
		$process->run();

		$process = new Process(['git', 'tag']);
		$process->run();

		$tags = trim($process->getOutput());
		$version = $this->getNextVersion($tags, $type, $target);


		$process = new Process(['git', 'tag', '-a', $version, '-m', $version . ' release']);
		$process->run();


		// push the changes to remote repository
		$process = new Process(['git', 'push', 'origin', '--tags', $target]);
		$process->run();


		// // push the new tag to remote repository
		// $process = new Process(['git', 'push', 'origin', $version]);
		// $process->run();



		$process = new Process(['git', 'checkout', $this->development]);
		$process->run();

		return $version;
	}


	protected function getNextVersion($version, $releaseType, $target) {
		$current = $this->parseVersion($version, $target);

		$major = explode(".", $current)[0];
		$minor = explode(".", $current)[1];
		$patch = explode(".", $current)[2];

		switch ($releaseType) {
			case static::RELEASE_MAJOR:
				$next = ($major + 1) . ".0.0";
				break;
			case static::RELEASE_MINOR:
				$next = $major . "." . ($minor + 1) . ".0";
				break;
			case static::RELEASE_PATCH:
				$next = $major . "." . $minor . "." . ($patch + 1);
				break;
		}

		return 'v' . $next . ($target == $this->staging ? '-beta' : '');
	}

	protected function parseVersion($tags, $target) {
		$default = '0.0.0';

		if (!$tags) {
			return $default;
		}

		$versions = collect(explode(PHP_EOL, $tags));

		$version = $versions->filter(function ($version) use ($target) {
			if ($target == $this->staging && strpos($version, static::BETA_LABEL)) {
				return true;
			} elseif (!strpos($version, static::BETA_LABEL)) {
				return true;
			}

			return false;
		})->map(function ($version) use ($target) {
			if ($target == $this->staging) {
				$version = explode(static::BETA_LABEL, $version)[0];
			}
			$version = explode("v", $version)[1];

			return $version;
		})->sortDesc()->first();

		if ($version == null) {
			$version = $default;
		}

		return $version;
	}
}
