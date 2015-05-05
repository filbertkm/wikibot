<?php

namespace Wikibot;

class Wiki {

	/**
	 * @var string
	 */
	private $wikiId;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var string
	 */
	private $apiPath;

	/**
	 * @param string $wikiId
	 */
	public function __construct( $wikiId ) {
		if ( !is_string( $wikiId ) ) {
			throw new \InvalidArgumentException( '$wikiId must be a string' );
		}

		$this->wikiId = $wikiId;
	}

	/**
	 * @param User $user
	 */
	public function setUser( User $user ) {
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * @param string $apiPath
	 */
	public function setApiPath( $apiPath ) {
		if ( !is_string( $apiPath ) ) {
			throw new \InvalidArgumentException( '$apiPath must be a string' );
		}

		$this->apiPath = $apiPath;
	}

	/**
	 * @return string
	 */
	public function getApiPath() {
		return $this->apiPath;
	}

}
