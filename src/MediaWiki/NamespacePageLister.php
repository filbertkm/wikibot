<?php

namespace Wikibot\MediaWiki;

use Wikibot\ApiClient;

class NamespacePageLister {

	/**
	 * @param ApiClient $apiClient
	 */
	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	public function getPageIds( $namespace, $limit = null ) {
		$params = [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => $namespace,
			'aplimit' => 20
		];

		$pageIds = [];
		$continue = true;
		$apContinue = null;

		$this->apiClient->login();

		while ( $continue ) {
			if ( $apContinue ) {
				$params['apcontinue'] = urlencode( $apContinue );
			}

			$results = $this->apiClient->post( $params );

			if ( !isset( $results['query']['allpages'] ) ) {
				break;
			}

			foreach ( $results['query']['allpages'] as $result ) {
				$pageIds[] = $result['pageid'];
			}

			if ( isset( $results['continue']['apcontinue'] ) ) {
				$apContinue = $results['continue']['apcontinue'];

				if ( $limit && count( $pageIds ) > $limit ) {
					$continue = false;
				}
			} else {
				$continue = false;
			}
		}

		sort( $pageIds );

		return $pageIds;
	}

}
