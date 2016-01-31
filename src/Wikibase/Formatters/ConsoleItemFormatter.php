<?php

namespace Wikibot\Wikibase\Formatters;

use Wikibot\Wikibase\DataModel\Item;
use Wikibot\Wikibase\DataModel\TermList;

class ConsoleItemFormatter implements ItemFormatter {

	private $statementGroupListFormatter;

	public function __construct() {
		$this->statementGroupListFormatter = new StatementGroupListFormatter();
	}

	/**
	 * @var Item $item
	 * @var string $langCode
	 */
	public function format( Item $item, $langCode ) {
		$lines = array();

		if ( $item->hasId() ) {
			$lines[] = $item->getId() . ":\n";
		}

		$lines[] = $this->formatTermList( $item->getLabels(), 'label', $langCode );
		$lines[] = $this->formatTermList( $item->getDescriptions(), 'description', $langCode );
		$lines[] = $this->formatAliases( $item, $langCode );
		$lines[] = "\nStatements:\n";
		$lines[] = $this->formatStatements( $item );

		return implode( "\n", $lines );
	}

	private function formatTermList( TermList $termList, $termType, $langCode ) {
		if ( $termList->hasTerm( $langCode ) ) {
			return $termList->getTerm( $langCode );
		}

		return "(no $termType)";
	}

	private function formatAliases( Item $item, $langCode ) {
		if ( $item->hasAliases( $langCode ) ) {
			return implode( " * ", $item->getAliases( $langCode ) );
		}

		return '(no aliases)';
	}

	private function formatStatements( Item $item ) {
		return $this->statementGroupListFormatter->format( $item->getStatementGroupList() );
	}

}
