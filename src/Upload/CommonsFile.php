<?php

namespace Wikibot\Upload;

class CommonsFile {

	private $title;

	private $text;

	public function __construct( $title, $text ) {
		$this->title = $title;
		$this->text = $text;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getText() {
		return $this->text;
	}

}
