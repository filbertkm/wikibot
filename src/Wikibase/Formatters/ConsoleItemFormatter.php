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
	 * @var string[] $parts To filter output.
	 */
	public function format( Item $item, $langCode, array $parts = array() ) {
		$lines = array();

		if ( $item->hasId() ) {
			$lines[] = $item->getId() . ":";
		}

		$lines['label'] = $this->formatTermList( $item->getLabels(), 'label', $langCode );
		$lines['description'] = $this->formatTermList( $item->getDescriptions(), 'description', $langCode );
		$lines['aliases'] = $this->formatAliases( $item, $langCode );
		$lines['statements'] = "\nStatements:\n\n" . $this->formatStatements( $item );

		if ( !empty( $parts ) ) {
			$lines = $this->filterParts( $parts, $lines );
		}

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

	private function filterParts( array $parts, array $lines ) {
		foreach ( $lines as $key => $line ) {
			if ( !in_array( $key, $parts ) ) {
				unset( $lines[$key] );
			}
		}

		return $lines;
	}

}
