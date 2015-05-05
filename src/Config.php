<?php

namespace Wikibot;

class Config {

	private $settings;

	public function __construct( array $settings ) {
		$this->settings = $settings;
	}

	public function getWiki( $wikiId ) {
		if ( array_key_exists( $wikiId, $this->settings['wikis'] ) ) {
			$wikiConfig = $this->settings['wikis'][$wikiId];

			$wiki = new Wiki( $wikiId );

			$user = new User( $wikiConfig['username'], $wikiConfig['password'] );
			$wiki->setUser( $user );

			$wiki->setApiPath( $wikiConfig['api-path'] );

			return $wiki;
		}
	}

}
