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
	 * @var int
	 */
	private $pageId;

	/**
	 * @var string|null
	 */
	private $itemId = null;

	/**
	 * @param string $titleText
	 * @param int $namespace
	 * @param string $wikiId
	 * @param int $pageId Defaults to 0 (current revision)
	 */
	public function __construct( $titleText, $namespace, $wikiId, $pageId = 0 ) {
		$this->titleText = $titleText;
		$this->namespace = $namespace;
		$this->wikiId = $wikiId;
		$this->pageId = $pageId;
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
	 * @return int
	 */
	public function getPageId() {
		return $this->pageId;
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

}
