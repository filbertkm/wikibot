<?php

namespace Wikibot\Wikibase\Query;

use Wikibase\DataModel\Entity\ItemId;

class QueryResult {

	/**
	 * @var ItemId[]
	 */
	private $itemIds;

	/**
	 * @param ItemId[] $itemIds
	 */
	public function __construct( array $itemIds ) {
		$this->itemIds = $itemIds;
	}

	public function getItemIds() {
		return $this->itemIds;
	}

}
