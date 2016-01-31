<?php

namespace Wikibot\Wikibase\DataModel;

use OutOfBoundsException;

class StatementGroupList {

	private $statementGroups;

	public function __construct( array $statementGroups ) {
		$this->statementGroups = $statementGroups;
	}

	public function hasStatementGroup( $propertyId ) {
		return array_key_exists( $propertyId, $this->statementGroups );
	}

	public function getStatementGroup( $propertyId ) {
		if ( !$this->hasStatementGroup( $propertyId ) ) {
			throw new OutOfBoundsException( 'No statement group exists for ' . $propertyId );
		}

		$statements = array();

		foreach ( $this->statementGroups[$propertyId] as $data ) {
			$statement = new Statement( $data );
			$guid = $statement->getGuid();

			$statements[$guid] = $statement;
		}

		return $statements;
	}

	public function getStatementGroups() {
		return $this->statementGroups;
	}

}
