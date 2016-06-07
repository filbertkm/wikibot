<?php

namespace Wikibot\Api;

class Response {

	/**
	 * @var array
	 */
	private $data;

	public static function newFromData( array $data ) {
		return new self( $data );
	}

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function getData() {
		return $this->data;
	}

}
