<?php

namespace Wikibot\Query;

use Wikibase\DataModel\Entity\ItemId;

class QueryCsvPrinter {

	private $outfile;

	public function __construct( $outfile ) {
		$this->outfile = $outfile;
	}

	public function output( QueryResult $queryResult ) {
		$itemIds = $queryResult->getItemIds();

		$lines = array();

		foreach ( $itemIds as $itemId ) {
			$lines[] = $itemId->getSerialization();
		}

		file_put_contents( $this->outfile, implode( "\n", $lines ) );
	}

}
