<?php

namespace Wikibot\Wikibase\DataModel;

interface StatementGroupProvider {

	/**
	 * @return StatementGroupList
	 */
	public function getStatementGroupList();

}
