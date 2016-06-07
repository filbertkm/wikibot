<?php

namespace Wikibot\Wikibase\Query;

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
	 * @return QueryBuilder
	 */
	public function getQuery() {
		return $this->queryBuilder;
	}

	/**
	 * @param string[] $pairs colon separated property-value pairs (e.g. 'P31:Q5')
	 */
	public function getPropertyEntityIdValueMultiMatches( array $pairs ) {
		$length = count( $pairs );

		for ( $i = 0; $i < $length; $i++ ) {
			list( $propertyId, $valueId ) = explode( ':', $pairs[$i] );

			if ( $i === 0 ) {
				$this->selectPair( $propertyId, $valueId );
			} else {
				$this->queryBuilder->also( "?item", "wdt:$propertyId", "wd:$valueId" );
			}
		}
	}

	public function setMinLon( $lon ) {
		$this->queryBuilder->also( "?item", "wdt:P625", "?coord" );
		$this->queryBuilder->also( "?item", "p:P625", "?coordinate" );
		$this->queryBuilder->also( "?coordinate", "psv:P625", "?coordinate_node" );
		$this->queryBuilder->also( "?coordinate_node", "wikibase:geoLongitude", "?lon" );

		$this->queryBuilder->filter( "?lon > $lon" );
	}

	private function selectPair( $propertyId, $valueId ) {
		$this->queryBuilder->select( '?item' )
			->where( "?item", "wdt:$propertyId", "wd:$valueId" );
	}

}
