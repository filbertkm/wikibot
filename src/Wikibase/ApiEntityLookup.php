<?php

namespace Wikibot\Wikibase;

use Wikibot\ApiClient;
use Wikibot\Wikibase\DataModel\Item;

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
				$item = Item::newFromArray( $value );

				return new EntityRevision( $item, $value['lastrevid'] );
			}
		}
	}

}
