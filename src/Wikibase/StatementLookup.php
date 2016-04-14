<?php

namespace Wikibot\Wikibase;

use Wikibot\ApiClient;

class StatementLookup {

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
	 * @param string $entityId
	 * @param string $propertyId
	 */
	public function getStatements( $entityId, $propertyId ) {
		$params = array(
			'action' => 'wbgetclaims',
			'entity' => $entityId,
			'property' => $propertyId
		);

		return $this->apiClient->get( $params );
	}

}
