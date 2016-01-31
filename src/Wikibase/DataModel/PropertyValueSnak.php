<?php

namespace Wikibot\Wikibase\DataModel;

class PropertyValueSnak {

	private $data;

	private $dataValueFactory;

	public function __construct( array $data ) {
		$this->data = $data;
		$this->dataValueFactory = new DataValueFactory();
	}

	public function getPropertyId() {
		return $this->data['property'];
	}

	public function getDataValue() {
		return $this->dataValueFactory->newDataValue( $this->data['datavalue'] );
	}

	public function getSnakType() {
		return 'value';
	}

}
