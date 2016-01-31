<?php

namespace Wikibot;

use Filbertkm\Http\HttpClient;
use MediaWiki\Sites\Lookup\SiteLookup;

class ApiClient {

	/**
	 * @var HttpClient
	 */
	private $httpClient;

	/**
	 * @var SiteLookup
	 */
	private $siteLookup;

	/**
	 * @var ?
	 */
	private $users;

	/**
	 * @var array
	 */
	private $user;

	/**
	 * @var string
	 */
	private $siteId;

	/**
	 * @var array
	 */
	private $tokens;

	/**
	 * @param HttpClient $httpClient
	 * @param SiteLookup $siteLookup
	 * @param ? $users
	 * @param string $siteId
	 */
	public function __construct( HttpClient $httpClient, SiteLookup $siteLookup, $users, $siteId ) {
		$this->httpClient = $httpClient;
		$this->siteLookup = $siteLookup;
		$this->users = $users;
		$this->siteId = $siteId;
	}

	public function get( array $params ) {
		$url = $this->getApiUrl() . '?' . $this->makeQueryString( $params );
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

		$site = $this->siteLookup->getSite( $this->siteId );

		$response = $this->httpClient->post(
			$this->getApiUrl(),
			$this->makeQueryString( $params )
		);

		return json_decode( $response, true );
	}

	public function upload( array $params ) {
		$params['token'] = $this->tokens['csrftoken'];
		$params['format'] = 'json';

		$response = $this->httpClient->multipart(
			$this->getApiUrl(),
			$params
		);

		return json_decode( $response, true );
	}

	public function login( $lgToken = null ) {
		if ( isset( $this->tokens ) ) {
			return true;
		}

		$siteId = $this->siteId;
		$this->user = $this->users['users'][$siteId];

		$params = array(
			'action' => 'login',
			'lgname' => $this->user['user'],
			'lgpassword' => $this->user['password']
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

	public function logout() {
		$this->post( array(
			'action' => 'logout'
		) );

		$this->httpClient->disconnect();
	}

	/**
	 * @return string
	 */
	private function getApiUrl() {
		$site = $this->siteLookup->getSite( $this->siteId );

		return $site->getApiUrl();
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
		$params = array(
			'format' => 'json'
		);

		if ( isset( $this->user ) && $this->user['bot'] === true ) {
			$params['bot'] = 1;
		}

		return $params;
	}

	/**
	 * @param scalar[] $params
	 *
	 * @return string
	 */
	private function makeQueryString( array $params ) {
		$params = array_merge( $this->getDefaultParams(), $params );

		$pairs = array();

		foreach ( $params as $key => $value ) {
			if ( !is_scalar( $key ) || !is_scalar( $value ) ) {
				throw new \InvalidArgumentException( 'Query string params must be scalars.' );
			}

			$pairs[] = urlencode( $key ) . '=' . urlencode( $value );
		}

		return implode( '&', $pairs );
	}

}
