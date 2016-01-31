<?php

namespace Wikibot\Wikibase\DataModel;

class Statement {

	private $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function getGuid() {
		return $this->data['id'];
	}

	public function getMainSnak() {
		$snakData = $this->data['mainsnak'];

		switch ( $snakData['snaktype'] ) {
			case 'value':
				return new PropertyValueSnak( $snakData );
			default:
				return new Snak( $snakData );
		}
	}

}
