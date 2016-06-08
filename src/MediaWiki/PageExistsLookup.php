<?php

namespace Wikibot\MediaWiki;

use Wikibot\ApiClient;

class PageExistsLookup {

	/**
	 * @param ApiClient $apiClient
	 */
	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	/**
	 * @param string
	 *
	 * @return bool
	 */
	public function pageExists( $title ) {
		$titles = explode( '|', $title );

		$params = [
			'action' => 'query',
			'prop' => 'info',
			'titles' => str_replace( '_', ' ', $titles[0] )
		];

		$info = $this->apiClient->get( $params );

		foreach ( $info['query']['pages'] as $page ) {
			return $page['title'] === $title && isset( $page['lastrevid'] );
		}

		return false;
	}

}
