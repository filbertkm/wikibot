<?php

namespace Wikibot\Wikibase;

use Wikibot\ApiClient;

class StatementCreator {

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
	 * @param string $valueId
	 * @param int $year
	 * @param int $baseRevId
	 */
	public function create( $entityId, $propertyId, $valueId, $year, $baseRevId ) {
		$json = json_encode( $this->buildData( $entityId, $propertyId, $valueId, $year ) );

		$params = array(
			'action' => 'wbsetclaim',
			'claim' => $json,
			'baserevid' => $baseRevId
		);

		return $this->apiClient->post( $params );
	}

	private function buildData( $entityId, $propertyId, $valueId, $year ) {
		return array(
			'id' => GuidGenerator::newStatmentGuid( $entityId ),
			'type' => 'statement',
			'mainsnak' => array(
				'snaktype' => 'value',
				'property' => $propertyId,
				'datavalue' => array(
					'value' => array(
						'entity-type' => 'item',
						'numeric-id' => ltrim( $valueId, 'Q' )
					),
					'type' => 'wikibase-entityid'
				)
			),
			'qualifiers' => array(
				'P585' => array(
					array(
						'snaktype' => 'value',
						'property' => 'P585',
						'datavalue' => array(
							'value' => array(
								'time' => "+$year-02-02T00:00:00Z",
								'timezone' => 0,
								'before' => 0,
								'after' => 0,
								'precision' => 11,
								'calendarmodel' => 'http://www.wikidata.org/entity/Q1985727'
							),
							'type' => 'time'
						),
						'datatype' => 'time'
					)
				)
			),
			'references' => array(
				array(
					'snaks' => array(
						'P1476' => array(
							array(
								'snaktype' => 'value',
								'property' => 'P1476',
								'datavalue' => array(
									'value' => array(
										'text' => 'Groundhog Day',
										'language' => 'en'
									),
									'type' => 'monolingualtext'
								),
								'datatype' => 'monolingualtext'
							)
						),
						'P123' => array(
							array(
								'snaktype' => 'value',
								'property' => 'P123',
								'datavalue' => array(
									'value' => array(
										'entity-type' => 'item',
										'numeric-id' => 21015842
									),
									'type' => 'wikibase-entityid'
								),
								'datatype' => 'wikibase-item'
							)
						),
						'P854' => array(
							array(
								'snaktype' => 'value',
								'property' => 'P854',
								'datavalue' => array(
									'value' => 'https://www.ncdc.noaa.gov/customer-support/education-resources/groundhog-day',
									'type' => 'string'
								),
								'datatype' => 'url'
							)
						),
						'P813' => array(
							array(
								'snaktype' => 'value',
								'property' => 'P813',
								'datavalue' => array(
									'value' => array(
										'time' => '+2016-02-02T00:00:00Z',
										'timezone' => 0,
										'before' => 0,
										'after' => 0,
										'precision' => 11,
										'calendarmodel' => 'http://www.wikidata.org/entity/Q1985727'
									),
									'type' => 'time'
								),
								'datatype' => 'time'
							)
						)
					)
				),
			),
			'rank' => 'normal'
		);
	}

}
