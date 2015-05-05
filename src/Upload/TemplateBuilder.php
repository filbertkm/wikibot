<?php

namespace Wikibot\Upload;

class TemplateBuilder {

	/**
	 * @var string
	 */
	private $filename;

	private $fields = array();

	public function __construct( $filename ) {
		if ( !is_string( $filename ) ) {
			throw new \InvalidArgumentException( '$filename must be a string.' );
		}

		$this->filename = $filename;
	}

	public function setField( $fieldName, $value ) {
		if ( in_array( $fieldName, $this->getValidFields() ) ) {
			$this->fields[$fieldName] = $value;
		}
	}

	public function getTemplate() {
		return $this->makeTemplate();
	}

	private function getValidFields() {
		return array(
			'Description',
			'Source',
			'Author',
			'Date',
			'Permission',
			'other_versions'
		);
	}

	private function makeTemplate() {
		$template = "{{Information\n";

		$template .= '|Date=' . $this->getDateField() . "\n";

		foreach( $this->getValidFields() as $field ) {
			if ( array_key_exists( $field, $this->fields ) ) {
				$template .= "|$field=" . $this->fields[$field] . "\n";
			} else {
				$template .= "|$field=\n";
			}
		}

		$template .= "}}";

		return $template;
	}

	private function getDateField() {
		return date( "Y-m-d", filemtime(  $this->filename ) );
	}

}
