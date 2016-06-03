<?php

namespace Wikibot\MediaWiki;

use Wikibot\ApiClient;

class PageCreator {

	/**
	 * @param ApiClient $apiClient
	 */
	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	public function create( $title, $text ) {
		$params = array(
			'action' => 'edit',
			'title' => $title,
			'text' => $text,
			'summary' => 'Import page'
		);

		return $this->apiClient->doEdit( $params );
	}

}
