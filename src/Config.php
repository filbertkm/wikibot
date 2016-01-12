<?php

namespace Wikibot;

use Wikimedia\Assert\Assert;

class Config {

	private $settings;

	public function __construct( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * @param string $settingName
	 *
	 * @return mixed
	 */
	public function get( $settingName ) {
		Assert::parameterType( 'string', $settingName, '$settingName' );

		if ( !array_key_exists( $settingName, $this->settings ) ) {
			throw new \InvalidArgumentException( 'Unknown setting: ' . $settingName );
		}

		return $this->settings[$settingName];
	}

}
