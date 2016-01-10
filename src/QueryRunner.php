<?php

namespace Wikibot;

class QueryRunner {

	private $queryBuilder;

	private $queryExecuter;

	public function __construct( array $queryPrefixes, $queryUrl ) {
		$this->queryBuilder = new QueryBuilder( $queryPrefixes );
		$this->queryExecuter = new QueryExecuter( $queryUrl );
	}

	public function getPropertyEntityIdValueMatches( $propertyId, $valueId ) {
		$this->queryBuilder->select( '?id' )
			->where( "?id", "wdt:$propertyId", "wd:$valueId" );

		$queryExecuter = new QueryExecuter( $this->queryUrl );
		$results = $queryExecuter->execute( $queryBuilder->getSPARQL() );

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
