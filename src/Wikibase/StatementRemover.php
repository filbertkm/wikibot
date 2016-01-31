<?php

namespace Wikibot\Wikibase;

use Wikibot\ApiClient;

class StatementRemover {

	/**
	 * @var ApiClient
	 */
	private $apiClient;

	/**
	 * @param ApiClient $apiClient
	 */
	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	/**
	 * @param string $statementGuid
	 */
	public function remove( $statementGuid, $baseRevId ) {
		$params = array(
			'action' => 'wbremoveclaims',
			'claim' => $statementGuid,
			'baserevid' => $baseRevId
		);

		return $this->apiClient->post( $params );
	}

}
