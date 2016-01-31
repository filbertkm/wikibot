<?php

namespace Wikibot\Wikibase;

use Wikibot\Wikibase\DataModel\Item;

class EntityRevision {

	/**
	 * @var Item
	 */
	private $item;

	/**
	 * @var int|null
	 */
	private $revisionId;

	public function __construct( Item $item, $revisionId = null ) {
		$this->item = $item;
		$this->revisionId = $revisionId;
	}

	public function getItem() {
		return $this->item;
	}

	public function getRevisionId() {
		return $this->revisionId;
	}

}
