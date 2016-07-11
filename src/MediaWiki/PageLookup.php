<?php

namespace Wikibot\MediaWiki;

use Wikibot\ApiClient;

class PageLookup {

	/**
	 * @param ApiClient $apiClient
	 */
	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	/**
	 * @param int
	 *
	 * @return Page
	 */
	public function getPage( $pageId ) {
		$params = [
			'action' => 'query',
			'prop' => 'revisions',
			'pageids' => $pageId,
			'rvprop' => 'content',
			'rvlimit' => 1
		];

		return $this->getPageFromParams( $params );
	}

	public function getPageByTitle( $title ) {
		$params = [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $title,
			'rvprop' => 'content',
			'rvlimit' => 1
		];

		return $this->getPageFromParams( $params );
	}

	private function getPageFromParams( array $params ) {
		$results = $this->apiClient->get( $params );

		if ( isset( $results['query']['pages'] ) ) {
			foreach ( $results['query']['pages'] as $pageData ) {
				$page = new Page(
					$pageData['title'],
					$pageData['ns'],
					$this->apiClient->getSiteId()
				);

				foreach ( $pageData['revisions'] as $revision ) {
					$page->setContent( $revision['*'] );
				}

				return $page;
			}
		}

		return null;
	}

}
