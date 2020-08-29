<?php

namespace Thetechyhub\Workflow;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thetechyhub\Workflow\Skeleton\SkeletonClass
 */
class WorkflowFacade extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'workflow';
	}
}
