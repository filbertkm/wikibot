<?php

namespace Wikibot\Wikibase\Query;

use Asparagus\QueryBuilder;
use Asparagus\QueryExecuter;
use Wikibase\DataModel\Entity\ItemId;

class QueryRunner {

	/**
	 * @var QueryExecuter
	 */
	private $queryExecuter;

	/**
	 * @var QueryBuilder
	 */
	private $queryBuilder;

	public function __construct( QueryExecuter $queryExecuter, QueryBuilder $queryBuilder ) {
		$this->queryExecuter = $queryExecuter;
		$this->queryBuilder = $queryBuilder;
	}

	public function doQuery() {
		$results = $this->queryExecuter->execute( $this->queryBuilder->getSPARQL() );

		return $this->parseResults( $results );
	}

	private function parseResults( array $results ) {
		$pattern = "/^http:\/\/www.wikidata.org\/entity\/([PQ]\d+)$/";
		$ids = array();

		foreach ( $results['bindings'] as $result ) {
			preg_match( $pattern, $result['item']['value'], $matches );

			if ( isset( $matches[1] ) ) {
				$ids[] = new ItemId( $matches[1] );
			}
		}

		return new QueryResult( $ids );
	}

}
