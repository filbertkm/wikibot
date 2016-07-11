<?php

namespace Wikibot\MediaWiki;

class Page {

	/**
	 * @var string
	 */
	private $titleText;

	/**
	 * @var int
	 */
	private $namespace;

	/**
	 * @var string
	 */
	private $wikiId;

	/**
	 * @var string|null
	 */
	private $content = null;

	/**
	 * @var string|null
	 */
	private $itemId = null;

	/**
	 * @param string $titleText
	 * @param int $namespace
	 * @param string $wikiId
	 */
	public function __construct( $titleText, $namespace, $wikiId ) {
		$this->titleText = $titleText;
		$this->namespace = $namespace;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return string
	 */
	public function getTitleText() {
		return $this->titleText;
	}

	/**
	 * @return int
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * @param string
	 */
	public function setItemId( $itemId ) {
		$this->itemId = $itemId;
	}

	/**
	 * @return string|null
	 */
	public function getItemId() {
		return $this->itemId;
	}

	/**
	 * @param string
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

	/**
	 * @return string|null
	 */
	public function getContent() {
		return $this->content;
	}

}
