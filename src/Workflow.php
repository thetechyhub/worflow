<?php

namespace Thetechyhub\Workflow;


class Workflow {


	/**
	 * Init Version Control with git
	 *
	 * @return void
	 */
	public static function versionControl() {
		VersionControle::install();
	}

	/**
	 * Run Scaffold Generator
	 *
	 * @return void
	 */
	public static function scaffold() {
		Scaffold::install();
	}
}
