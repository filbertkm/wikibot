<?php

namespace Wikibot\Query;

use Asparagus\QueryBuilder;
use Asparagus\QueryExecuter;

class QueryRunner {

	private $queryBuilder;

	private $queryExecuter;

	public function __construct( array $queryPrefixes, $queryUrl ) {
		$this->queryBuilder = new QueryBuilder( $queryPrefixes );
		$this->queryExecuter = new QueryExecuter( $queryUrl );
	}

	public function getPropertyEntityIdValueMatches( $propertyId, $valueId ) {
		$this->selectPair( $propertyId, $valueId );

		return $this->doQuery();
	}

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

		return $this->doQuery();
	}

	private function selectPair( $propertyId, $valueId ) {
		$this->queryBuilder->select( '?id' )
			->where( "?id", "wdt:$propertyId", "wd:$valueId" );
	}

	private function doQuery() {
		$results = $this->queryExecuter->execute( $this->queryBuilder->getSPARQL() );

		return $this->parseResults( $results );
	}

	private function parseResults( array $results ) {
		$pattern = "/^http:\/\/www.wikidata.org\/entity\/([PQ]\d+)$/";
		$ids = array();

		foreach ( $results['bindings'] as $result ) {
			preg_match( $pattern, $result['id']['value'], $matches );

			if ( isset( $matches[1] ) ) {
				$ids[] = $matches[1];
			}
		}

		return $ids;
	}

}
