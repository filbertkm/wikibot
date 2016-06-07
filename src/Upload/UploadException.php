<?php

namespace Wikibot\Upload;

class UploadException extends \Exception {

	private $errorCode;

	public function __construct( $errorCode, $info ) {
		$this->errorCode = $errorCode;

		parent::__construct( $info );
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

}
