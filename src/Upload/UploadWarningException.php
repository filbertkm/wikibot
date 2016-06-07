<?php

namespace Wikibot\Upload;

class UploadWarningException extends UploadException {

	private $warnings;

	public function __construct( array $warnings, $errorCode, $info ) {
		$this->warnings = $warnings;

		parent::__construct( $errorCode, $info );
	}

	public function getWarnings() {
		return $this->warnings;
	}

}
