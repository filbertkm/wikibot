<?php

namespace Wikibot\Wikibase\Query;

use Wikibase\DataModel\Entity\ItemId;

class QueryCsvPrinter {

	public function output( QueryResult $queryResult ) {
		$itemIds = $queryResult->getItemIds();

		$lines = array();

		foreach ( $itemIds as $itemId ) {
			$lines[] = $itemId->getSerialization();
		}

		return implode( "\n", $lines );
	}

}
