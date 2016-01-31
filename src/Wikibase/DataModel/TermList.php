<?php

namespace Wikibot\Wikibase\DataModel;

use OutOfBoundsException;

class TermList {

	/**
	 * @var array
	 */
	private $terms;

	/**
	 * @param array $terms
	 */
	public function __construct( array $terms ) {
		$this->terms = $terms;
	}

	/**
	 * @param string $langCode
	 *
	 * @return bool
	 */
	public function hasTerm( $langCode ) {
		return array_key_exists( $langCode, $this->terms );
	}

	/**
	 * @param string $langCode
	 *
	 * @throws OutOfBoundsException
	 * @return string
	 */
	public function getTerm( $langCode ) {
		if ( !$this->hasTerm( $langCode ) ) {
			throw new OutOfBoundsException( "Term not set for $langCode" );
		}

		return $this->terms[$langCode]['value'];
	}

	/**
	 * @return array
	 */
	public function getTerms() {
		return $this->terms;
	}

}
