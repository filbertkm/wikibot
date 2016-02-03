<?php

namespace Wikibot\Wikibase;

use Wikibot\ApiClient;

class SnakBuilder {

	public function getEntityIdValueSnak( $propertyId, $valueId ) {
		return array(
			'snaktype' => 'value',
			'property' => $propertyId,
			'datavalue' => array(
				'value' => array(
					'entity-type' => 'item',
					'numeric-id' => ltrim( $valueId, 'Q' )
				),
				'type' => 'wikibase-entityid'
			),
			'datatype' => 'wikibase-item'
		);
	}

	public function getUrlValueSnak( $propertyId, $url ) {
		return array(
			'snaktype' => 'value',
			'property' => $propertyId,
			'datavalue' => array(
				'value' => $url,
				'type' => 'string'
			),
			'datatype' => 'url'
		);
	}

	public function getMonolingualValueSnak( $propertyId, $text, $languageCode ) {
		return array(
			'snaktype' => 'value',
			'property' => $propertyId,
			'datavalue' => array(
				'value' => array(
					'text' => $text,
					'language' => $languageCode
				),
				'type' => 'monolingualtext'
			),
			'datatype' => 'monolingualtext'
		);
	}

	public function getTimeValueSnak( $propertyId, $year ) {
		return array(
			'snaktype' => 'value',
			'property' => $propertyId,
			'datavalue' => $this->getTimeValue( $year ),
			'datatype' => 'time'
		);
	}

	private function getTimeValue( $year ) {
		return array(
			'value' => array(
				'time' => "+$year-02-02T00:00:00Z",
				'timezone' => 0,
				'before' => 0,
				'after' => 0,
				'precision' => 11,
				'calendarmodel' => 'http://www.wikidata.org/entity/Q1985727'
			),
			'type' => 'time'
		);
	}

}
