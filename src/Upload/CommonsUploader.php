<?php

namespace Wikibot\Upload;

use Wikibot\ApiClient;

class CommonsUploader {

	private $apiClient;

	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	public function upload( $title, $file, $text ) {
		$params = array(
			'action' => 'upload',
			'file' => "@$file",
			'text' => $text,
			'filename' => "$title.jpg"
		);

		return $this->apiClient->upload( $params );
	}

}
