<?php

namespace Thetechyhub\Workflow\Commands;

use Illuminate\Console\Command;

use Thetechyhub\Workflow\Workflow;

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
		Workflow::versionControl();

		$this->info('Git version controll scaffolding is complete.');
		$this->comment('You can run "git remote add origin REPO_URL" to set your remote repository.');
		$this->line('');
		$this->line('');


		Workflow::scaffold();

		$this->info('Project structure scaffolding is complete.');
		$this->line('');
		$this->line('');

		$this->comment('Run "composer update" to install composer dependencies.');
		$this->line('');
		$this->line('');

		$this->comment('Run "npm install && npm run dev" to install and compile npm packages.');
		$this->line('');
		$this->line('');

		$this->comment('Run "npx cypress open" to generate cypress scaffolding.');
		$this->line('');
		$this->line('');

		$this->comment('If you are using cypress, run "php artisan cypress:boilerplate" to integrate laravel with cypress.');
		$this->line('');
		$this->line('');
	}
}
