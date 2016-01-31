<?php

namespace Wikibot\Wikibase\DataModel;

class Snak {

	private $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function getPropertyId() {
		return $this->data['property'];
	}

	public function getSnakType() {
		return $this->data['snaktype'];
	}

}
