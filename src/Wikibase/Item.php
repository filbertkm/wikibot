<?php

namespace Wikibot\Wikibase;

class Item {

	private $labels;

	private $descriptions;

	private $aliases;

	private $statements;

	private $siteLinks;

	public static function newFromArray( array $data ) {
		return new self(
			$data['labels'],
			$data['descriptions'],
			$data['aliases'],
			$data['claims'],
			$data['sitelinks']
		);
	}

	public function __construct(
		array $labels,
		array $descriptions,
		array $aliases,
		array $statements,
		array $siteLinks
	) {
		$this->labels = $labels;
		$this->descriptions = $descriptions;
		$this->aliases = $aliases;
		$this->statements = $statements;
		$this->siteLinks = $siteLinks;
	}

	public function getLabels() {
		return $this->labels;
	}

	public function getLabel( $langCode ) {
		if ( array_key_exists( $langCode, $this->labels ) ) {
			return $this->labels[$langCode]['value'];
		}
	}

}
