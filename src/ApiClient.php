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
	 * @var array
	 */
	private $tokens;

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

		if ( !isset( $this->tokens ) ) {
			$this->httpClient->disconnect();
		}

		return json_decode( $response, true );
	}

	public function post( array $params ) {
		if ( isset( $this->tokens['csrftoken'] ) ) {
			$params['token'] = $this->tokens['csrftoken'];
		}

		$response = $this->httpClient->post(
			$this->wiki->getApiPath(),
			$this->makeQueryString( $params )
		);

		return json_decode( $response, true );
	}

	public function upload( array $params ) {
		$params['token'] = $this->tokens['csrftoken'];
		$params['format'] = 'json';

		$response = $this->httpClient->multipart(
			$this->wiki->getApiPath(),
			$params
		);

		return json_decode( $response, true );
	}

	public function login( $lgToken = null ) {
		if ( isset( $this->tokens ) ) {
			return true;
		}

		$user = $this->wiki->getUser();

		$params = array(
			'action' => 'login',
			'lgname' => $user->getUserName(),
			'lgpassword' => $user->getPassword()
		);

		if ( $lgToken !== null ) {
			$params['lgtoken'] = $lgToken;
		}

		$response = $this->post( $params );

		if ( $response['login']['result'] === 'NeedToken' ) {
			return $this->login( $response['login']['token'] );
		} elseif ( $response['login']['result'] === 'Success' ) {
			$this->setTokens();

			return true;
		}

		return false;
	}

	private function setTokens() {
		$params = array(
			'action' => 'query',
			'meta' => 'tokens'
		);

		$response = $this->post( $params );

		if ( isset( $response['query']['tokens'] ) ) {
			$this->tokens = $response['query']['tokens'];
		}
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
