<?php

namespace Thetechyhub\Workflow;


class Workflow {


	/**
	 * Init Version Control with git
	 *
	 * @return void
	 */
	public static function versionControl() {
		(new VersionControle)->install();
	}

	/**
	 * Run Scaffold Generator
	 *
	 * @return void
	 */
	public static function scaffold() {
		Scaffold::install();
	}


	/**
	 * Run deploy on target branch
	 *
	 * @param string $target
	 * @return void
	 */
	public static function deploy($target, $type) {
		return (new VersionControle)->deploy($target, $type);
	}
}
