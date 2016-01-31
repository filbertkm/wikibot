<?php

namespace Wikibot\Wikibase\Formatters;

use Wikibot\Wikibase\DataModel\Item;

interface ItemFormatter {

	/**
	 * @var Item $item
	 * @var string $languageCode
	 */
	public function format( Item $item, $languageCode );

}
