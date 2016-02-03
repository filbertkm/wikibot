<?php

namespace Wikibot\Wikibase;

use Wikibot\ApiClient;

class StatementCreator {

	/**
	 * @var ApiClient
	 */
	private $apiClient;

	/**
	 * @var SnakBuilder
	 */
	private $snakBuilder;

	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * @param ApiClient $apiClient
	 */
	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
		$this->snakBuilder = new SnakBuilder();
	}

	/**
	 * @param string $entityId
	 * @param string $propertyId
	 * @param string $valueId
	 */
	public function newStatement( $entityId, $propertyId, $valueId ) {
		$this->data = array(
			'id' => GuidGenerator::newStatmentGuid( $entityId ),
			'type' => 'statement',
			'mainsnak' => $this->snakBuilder->getEntityIdValueSnak( $propertyId, $valueId ),
			'qualifiers' => array(),
			'references' => array()
		);
	}

	public function addQualifier( $propertyId, $snakArray ) {
		$this->data['qualifiers'][$propertyId][] = $snakArray;
	}

	public function addReference( array $snaksArray ) {
		$this->data['references'][] = array(
			'snaks' => $snaksArray
		);
	}

	public function create( $baseRevId ) {
		$params = array(
			'action' => 'wbsetclaim',
			'claim' => json_encode( $this->data ),
			'baserevid' => $baseRevId
		);

		return $this->apiClient->post( $params );
	}

	public function getData() {
		return $this->data;
	}

}
