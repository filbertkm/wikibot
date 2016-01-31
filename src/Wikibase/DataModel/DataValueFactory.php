<?php

namespace Wikibot\Wikibase\DataModel;

class DataValueFactory {

	public function newDataValue( array $data ) {
		switch( $data['type'] ) {
			case 'wikibase-entityid':
				return new EntityIdValue( $data );
			default:
				return new DataValue( $data );
		}
	}

}
