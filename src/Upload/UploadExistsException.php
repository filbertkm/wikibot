<?php

namespace Wikibot\Upload;

class UploadExistsException extends UploadException {

	public function __construct( $errorCode, $info ) {
		parent::__construct( $errorCode, $info );
	}

}
