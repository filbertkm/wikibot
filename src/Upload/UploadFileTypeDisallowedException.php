<?php

namespace Wikibot\Upload;

class UploadFileTypeDisallowedException extends UploadException {

	private $fileType;

	private $allowedFileTypes;

	public function __construct( $fileType, $allowedFileTypes, $errorCode, $info ) {
		$this->fileType = $fileType;
		$this->allowedFileTypes = $allowedFileTypes;

		parent::__construct( $errorCode, $info );
	}

	public function getFileType() {
		return $this->fileType;
	}

	public function getAllowedFileTypes() {
		return $this->allowedFileTypes;
	}

}
