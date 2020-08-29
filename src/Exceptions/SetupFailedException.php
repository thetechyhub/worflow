<?php

namespace Thetechyhub\Workflow\Exceptions;

use Exception;

class SetupFailedException extends Exception {

	public function __construct($message = null, $code = 0, Exception $previous = null) {
		$message = $message ?? 'Setup Process Has Failed.';

		parent::__construct($message, $code, $previous);
	}
}
