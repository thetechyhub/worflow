<?php

namespace Thetechyhub\Workflow\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Process\Process;
use Thetechyhub\Workflow\VersionControle;
use Thetechyhub\Workflow\Workflow;

class DeployCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'workflow:deploy {--s| server=local : Define where are you running this command, you can select between local, staging and live.}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploy your changes to staging or live server.';

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
		$server = $this->option('server');

		if (!$server) {
			$this->line('');
			$this->error("You must provide a target option");
			$this->comment('Run "php artisan workflow:deploy --help" to get some guidance.');
			$this->line('');
			return 0;
		}

		if ($server == 'local') {
			$this->deployOnLocal();
		} else {
			Workflow::serverDeploy($server);

			$this->info("Deployment to {$server} is complete");
		}
	}

	public function deployOnLocal() {
		$versionControl = new VersionControle;

		$target = $this->choice('Select your deploy target?', [
			$versionControl->staging,
			$versionControl->release
		], 0);


		$this->info("This is a guide to help you choose the right release type");


		$headers = ["Type", "Description"];
		$categories = [
			[
				"Type" => "Major",
				"Description" => "A Major release is used to signify that a new feature \n was added or removed from the source code.\n "
			],
			[
				"Type" => "Minor",
				"Description" => "A Minor release is used to signify that functionality has been added,\n but the code is otherwise backward compatible.\n "
			],
			[
				"Type" => "Patch",
				"Description" => "A Patch release is used to signify that the code changes in this revision \n has not added any new features or API changes and is backward compatible with the previous version. \n It is most typically used to signify a bug fix.\n "
			]
		];

		$this->table($headers, $categories);

		$type = $this->choice('Select Release Type?', [
			"Major",
			"Minor",
			"Patch"
		], 0);

		$this->info("Deploying...");

		$version = Workflow::deploy($target, $type);

		$this->line('');
		$this->info("Version {$version} was deployed to {$target} successfully");
	}
}
