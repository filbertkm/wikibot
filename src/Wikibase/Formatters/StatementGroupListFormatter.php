<?php

namespace Wikibot\Wikibase\Formatters;

use Wikibot\Wikibase\DataModel\StatementGroupList;

class StatementGroupListFormatter {

	public function format( StatementGroupList $statementGroupList ) {
		return implode(
			"\n",
			array_keys( $statementGroupList->getStatementGroups() )
		);
	}

}
