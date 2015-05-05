<?php

namespace Wikibot\MediaWiki;

/**

array (
  'entities' =>
  array (
    'Q22' =>
    array (
      'pageid' => 203,
      'ns' => 0,
      'title' => 'Q22',
      'lastrevid' => 13598,
      'modified' => '2015-05-04T17:12:01Z',
      'id' => 'Q22',
      'type' => 'item',
      'aliases' =>
      array (
        'en' =>
        array (
          0 =>
          array (
            'language' => 'en',
            'value' => 'kitty',
          ),

*/

class Page {

	/**
	 * var Revision
	 */
	private $revision;

	/**
	 * @var int
	 */
	private $pageId;

	/**
	 * @param Revision $revision
	 * @param int $pageId
	 */
	public function __construct( Revision $revision, $pageId ) {
		$this->revision = $revision;
		$this->pageId = $pageId;
	}

	/**
	 * @return Revision
	 */
	public function getRevision() {
		return $this->revision;
	}

	/**
	 * @return int
	 */
	public function getPageId() {
		return $this->pageId;
	}

}
