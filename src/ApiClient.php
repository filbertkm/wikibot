<?php

namespace Wikibot;

class ApiClient {

	/**
	 * @var HttpClient
	 */
	private $httpClient;

	/**
	 * @var Wiki
	 */
	private $wiki;

	/**
	 * @var bool
	 */
	private $loggedIn = false;

	/**
	 * @param Wiki $wiki
	 */
	public function __construct( HttpClient $httpClient, Wiki $wiki ) {
		$this->httpClient = $httpClient;
		$this->wiki = $wiki;
	}

	public function get( array $params ) {
		$url = $this->wiki->getApiPath() . '?' . $this->makeQueryString( $params );
		$response = $this->httpClient->get( $url );

		if ( $this->loggedIn === false ) {
			$this->httpClient->disconnect();
		}

		return $response;
	}

	public function post( array $params ) {

	}

	public function login( array $params ) {

	}

	public function doEdit( array $params ) {

	}

	/**
	 * @return string[]
	 */
	private function getDefaultParams() {
		return array(
			'format' => 'json'
		);
	}

	/**
	 * @param scalar[] $params
	 *
	 * @return string
	 */
	private function makeQueryString( array $params ) {
		$params = array_merge( $this->getDefaultParams(), $params );

		$pairs = array();

		foreach( $params as $key => $value ) {
			if ( !is_scalar( $key ) || !is_scalar( $value ) ) {
				throw new \InvalidArgumentException( 'Query string params must be scalars.' );
			}

			$pairs[] = urlencode( $key ) . '=' . urlencode( $value );
		}

		return implode( '&', $pairs );
	}

}
