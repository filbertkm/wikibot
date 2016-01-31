<?php

namespace Wikibot\Wikibase\DataModel;

use UnexpectedValueException;

class EntityIdValue {

	private $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function getEntityId() {
		switch( $this->data['value']['entity-type'] ) {
			case 'item':
				return 'Q' . $this->data['value']['numeric-id'];
			case 'property':
				return 'P' . $this->data['value']['numeric-id'];
			default:
				throw new UnexpectedValueException( 'Unknown entity type' );
		}
	}

}
