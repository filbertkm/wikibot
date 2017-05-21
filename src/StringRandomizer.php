<?php

namespace Wikibot;

class StringRandomizer {

	public static function makeString( $length = 12 ) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$randomString .= $characters[rand(0, strlen( $characters ) - 1)];
		}

		return $randomString;
	}
}
