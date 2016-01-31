<?php

namespace Wikibot\Wikibase\DataModel;

interface DescriptionProvider {

	/**
	 * @return TermList
	 */
	public function getDescriptions();

}
