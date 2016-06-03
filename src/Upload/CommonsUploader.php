<?php

namespace Wikibot\Upload;

use Wikibot\ApiClient;

class CommonsUploader {

	private $apiClient;

	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	/**
	 * @param string $fileTitle Title of the file page
	 * @param string $filePath Path to the file
	 * @param string $filePageText Text for the file page
	 */
	public function upload( $fileTitle, $filePath, $filePageText ) {
		$params = array(
			'action' => 'upload',
			'file' => "@$filePath",
			'text' => $filePageText,
			'filename' => $fileTitle
		);

		return $this->apiClient->upload( $params );
	}

}
