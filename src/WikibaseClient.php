<?php

namespace Wikibot;

class WikibaseClient {

	/**
	 * @var ApiClient
	 */
	private $apiClient;

	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	public function getClaims( $entityId, $propertyId ) {
		echo __METHOD__;
		$params = array(
			'action' => 'wbgetclaims',
			'entity' => $entityId,
			'property' => $propertyId
		);

		return $this->apiClient->get( $params );
	}

	public function createClaim( $entityId, $propertyId, $data ) {
		$params = array(
			'action' => 'wbcreateclaim',
			'entity' => $entityId,
			'property' => $propertyId,
			'snaktype' => 'somevalue',
//			'value' => json_encode( $data )
		);

		return $this->doEdit( $params );
	}

	public function setClaim( $data, $baseRev ) {
		$params = array(
			'action' => 'wbsetclaim',
			'claim' => json_encode( $data ),
			'baserevid' => $baseRev
		);

		return $this->doEdit( $params );
	}

	public function setReference(
		$statement,
		$refSnaks,
		$baseRev,
		$snaksOrder = null,
		$referenceHash = null
	) {
		$params = array(
			'action' => 'wbsetreference',
			'statement' => $statement,
			'snaks' => json_encode( $refSnaks ),
			'baserevid' => $baseRev
		);

		if ( $snaksOrder !== null ) {
			$params['snaks-order'] = json_encode( $snaksOrder );
		}

		if ( $referenceHash !== null ) {
			$params['reference'] = $referenceHash;
		}

		return $this->doEdit( $params );
	}

	public function getBadgeItems() {
		$params = array(
			'action' => 'wbavailablebadges'
		);

		$result = $this->apiClient->get( $params );

		$data = json_decode( $result, true );

		return $data['badges'];
	}

	private function doEdit( array $params ) {
		$this->apiClient->login();

		return $this->apiClient->post( $params );
	}

	private function getValueArray( $valueType, $value ) {
		switch( $valueType ) {
			case 'item':
				return array(
					'entity-type' => $valueType,
					'numeric-id' => (int)$value
				);
				break;
			case 'globecoordinate':
				//return $this->getCoordinateArray( $value['lat'], $value['lon'] );
				break;
			case 'time':
				return array(
					'time' => $value,
					'timezone' => 0,
					'before' => 0,
					'after' => 0,
					'precision' => 11,
					'calendarmodel' => 'http://www.wikidata.org/entity/Q1985727'
				);
				break;
			case 'string':
				return $value;
				break;
			default:
				return $value;
		}
	}

}
