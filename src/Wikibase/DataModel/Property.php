<?php

namespace Wikibot\Wikibase\DataModel;

use OutOfBoundsException;

class Property implements LabelProvider, DescriptionProvider, StatementGroupProvider {

	/**
	 * @var string|null
	 */
	private $id = null;

	/**
	 * @var TermList
	 */
	private $labels;

	/**
	 * @var TermList
	 */
	private $descriptions;

	private $aliases;

	/**
	 * @var StatementGroupList
	 */
	private $statementGroupList;

	public static function newFromArray( array $data ) {
		$item = new self(
			new TermList( $data['labels'] ),
			new TermList( $data['descriptions'] ),
			$data['aliases'],
			new StatementGroupList( $data['claims'] )
		);

		if ( isset( $data['id'] ) ) {
			$item->setId( $data['id'] );
		}

		return $item;
	}

	public function __construct(
		TermList $labels,
		TermList $descriptions,
		array $aliases,
		StatementGroupList $statementGroupList
	) {
		$this->labels = $labels;
		$this->descriptions = $descriptions;
		$this->aliases = $aliases;
		$this->statementGroupList = $statementGroupList;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string|null
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return TermList
	 */
	public function getLabels() {
		return $this->labels;
	}

	/**
	 * @return TermList
	 */
	public function getDescriptions() {
		return $this->descriptions;
	}

	/**
	 * @param string $langCode
	 *
	 * @return bool
	 */
	public function hasAliases( $langCode ) {
		return array_key_exists( $langCode, $this->aliases );
	}

	/**
	 * @param string $langCode
	 *
	 * @return array
	 */
	public function getAliases( $langCode ) {
		if ( !$this->hasAliases( $langCode ) ) {
			throw new OutOfBoundsException( 'Aliases not set for ' . $langCode );
		}

		$aliasList = array();

		foreach ( $this->aliases[$langCode] as $alias ) {
			$aliasList[] = $alias['value'];
		}

		return $aliasList;
	}

	public function getStatementGroupList() {
		return $this->statementGroupList;
	}

}
