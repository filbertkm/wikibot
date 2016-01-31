<?php

namespace Wikibot\Wikibase\DataModel;

interface LabelProvider {

	/**
	 * @return TermList
	 */
	public function getLabels();

}
