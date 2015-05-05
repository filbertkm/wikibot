<?php

namespace Wikibot;

class User {

	private $username;

	private $password;

	public function __construct( $username, $password ) {
		$this->username = $username;
		$this->password = $password;
	}

	public function getUserName() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

}
