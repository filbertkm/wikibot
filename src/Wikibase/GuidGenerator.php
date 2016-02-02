<?php

namespace Wikibot\Wikibase;

class GuidGenerator {

	public static function newStatmentGuid( $entityId ) {
		return $entityId . '$' . self::newGuid();
	}

    private static function newGuid() {
        if ( function_exists( 'com_create_guid' ) ) {
            return trim( com_create_guid(), '{}' );
        }

        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand( 0, 65535 ),
            mt_rand( 0, 65535 ),
            mt_rand( 0, 65535 ),
            mt_rand( 16384, 20479 ),
            mt_rand( 32768, 49151 ),
            mt_rand( 0, 65535 ),
            mt_rand( 0, 65535 ),
            mt_rand( 0, 65535 )
        );
    }

}
