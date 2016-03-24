<?php

namespace Wikibot\Query;

use Asparagus\QueryBuilder;

class SparqlBuilder {

	/**
	 * @var QueryBuilder
	 */
	private $queryBuilder;

	public function __construct( QueryBuilder $queryBuilder ) {
		$this->queryBuilder = $queryBuilder;
	}

	/**
	 * @param string $propertyId
	 * @param string $valueId
	 *
	 * @return QueryBuilder
	 */
	public function getPropertyEntityIdValueMatches( $propertyId, $valueId ) {
		$this->selectPair( $propertyId, $valueId );

		return $this->queryBuilder;
	}

	/**
	 * @param string[] $pairs colon separated property-value pairs (e.g. 'P31:Q5')
	 *
	 * @return QueryBuilder
	 */
	public function getPropertyEntityIdValueMultiMatches( array $pairs ) {
		$length = count( $pairs );

		for ( $i = 0; $i < $length; $i++ ) {
			list( $propertyId, $valueId ) = explode( ':', $pairs[$i] );

			if ( $i === 0 ) {
				$this->selectPair( $propertyId, $valueId );
			} else {
				$this->queryBuilder->also( "?id", "wdt:$propertyId", "wd:$valueId" );
			}
		}

		return $this->queryBuilder;
	}

	private function selectPair( $propertyId, $valueId ) {
		$this->queryBuilder->select( '?id' )
			->where( "?id", "wdt:$propertyId", "wd:$valueId" );
	}

}
