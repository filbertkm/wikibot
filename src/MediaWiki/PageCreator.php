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

	public function create( $title, $text, $summary ) {
		$params = array(
			'action' => 'edit',
			'title' => $title,
			'text' => $text,
			'summary' => $summary
		);

		return $this->apiClient->doEdit( $params );
	}

}
