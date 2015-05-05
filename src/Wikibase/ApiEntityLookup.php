<?php

namespace Wikibot\Wikibase;

class ApiEntityLookup {

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
	 */
	public function getEntity( $entityId ) {
		$params = array(
			'action' => 'wbgetentities',
			'ids' => $entityId
		);

		$data = $this->apiClient->get( $params );

		foreach( $data['entities'] as $key => $value ) {
			if ( $key === $entityId ) {
				return Item::newFromArray( $value );
			}
		}

	}

}
