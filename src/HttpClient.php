<?php

namespace Wikibot;

class HttpClient {

	/**
	 * @var string
	 */
	private $userAgent;

	/**
	 * @var resource
	 */
	private $conn;

	/**
	 * @var string
	 */
	private $cookieFileName;

	/**
	 * @param string $userAgent
	 */
	public function __construct( $userAgent ) {
		if ( !is_string( $userAgent ) ) {
			throw new \InvalidArgumentException( '$userAgent must be a string.' );
		}

		$this->userAgent = $userAgent;
	}

	public function connect() {
		$this->conn = curl_init();

		curl_setopt( $this->conn, CURLOPT_COOKIEFILE, $this->getCookieFileName() );
		curl_setopt( $this->conn, CURLOPT_COOKIEJAR, $this->getCookieFileName() );
		curl_setopt( $this->conn, CURLOPT_USERAGENT, $this->userAgent );
		curl_setopt( $this->conn, CURLOPT_SSL_VERIFYPEER, false );
	}

	public function disconnect() {
		curl_close( $this->conn );
		unset( $this->conn );

		$this->destroyCookie();
	}

	/**
	 * @param string $url
	 */
	public function get( $url ) {
		if ( !is_string( $url ) ) {
			throw new \InvalidArgumentException( '$url must be a string' );
		}

		if ( !isset( $this->conn ) ) {
			$this->connect();
		}

		$this->setCurlGetOpts( $url );

		return $this->request();
	}

	/**
	 * @param string $url
	 * @param string $postFields
	 */
	public function post( $url, $postFields = null, $headers = array() ) {
		if ( !is_string( $url ) ) {
			throw new \InvalidArgumentException( '$url must be a string' );
		}

		if ( !is_string( $postFields ) && !is_array( $postFields ) && !is_null( $postFields ) ) {
			throw new \InvalidArgumentException( '$postFields must be a string, array or null' );
		}

		if ( !isset( $this->conn ) ) {
			$this->connect();
		}

		$headers[] = 'X-Wikimedia-Debug: 1';

		$this->setCurlPostOpts( $url, $postFields, $headers );

		return $this->request();
	}

	public function multipart( $url, $postFields ) {
		$headers = array(
			'Content-Type' => 'multipart/form-data'
		);

		curl_setopt( $this->conn, CURLOPT_TIMEOUT, 500 );

		return $this->post( $url, $postFields, $headers );
	}

	private function request() {
		$response = curl_exec( $this->conn );

		if ( $response === false ) {
			$response = curl_error( $this->conn );
		}

		curl_reset( $this->conn );

		return $response;
	}

	private function setCurlGetOpts( $url ) {
		curl_setopt( $this->conn, CURLOPT_URL, $url );
		curl_setopt( $this->conn, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->conn, CURLOPT_HEADER, 0 );
		curl_setopt( $this->conn, CURLOPT_HTTPHEADER, array( null ) );
		curl_setopt( $this->conn, CURLOPT_POST, false );
		curl_setopt( $this->conn, CURLOPT_POSTFIELDS, null );
	}

	private function setCurlPostOpts( $url, $postFields, $headers = array() ) {
		curl_setopt( $this->conn, CURLOPT_URL, $url );
		curl_setopt( $this->conn, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->conn, CURLOPT_HEADER, 0 );
		curl_setopt( $this->conn, CURLOPT_POST, true );
		curl_setopt( $this->conn, CURLOPT_HTTPHEADER, array_merge( array( 'Expect:' ), $headers ) );
		curl_setopt( $this->conn, CURLOPT_POSTFIELDS, $postFields );
	}

	private function getCookieFileName() {
		if ( !isset( $this->cookieFileName ) ) {
			$this->cookieFileName = tempnam( sys_get_temp_dir(), 'wikibot' );
		}

		return $this->cookieFileName;
	}

	private function destroyCookie() {
		if ( isset( $this->cookieFileName ) ) {
			unlink( $this->cookieFileName );
		}
	}

}
