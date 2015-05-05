<?php

namespace Wikibot\Upload;

use DOMDocument;
use DOMXPath;

class NARAClintonScraper {

	/**
	 * @var HttpClient
	 */
	private $httpClient;

	/**
	 * @var string
	 */
	private $baseUrl;

	/**
	 * @param HttpClient $httpClient
	 */
	public function __construct( HttpClient $httpClient, $baseUrl ) {
		if ( !is_string( $baseUrl ) ) {
			throw new \InvalidArgumentException( '$baseUrl must be a string.' );
		}

		$this->httpClient = $httpClient;
		$this->baseUrl = $baseUrl;
	}

	public function getItemData( $number ) {
		$page = $this->httpClient->get( $this->baseUrl . $number );

		$doc = new DOMDocument();
		$doc->loadHTML( $page );

		$xpath = new DOMXPath( $doc );

		$data = array();

		$imageElements = $xpath->query( '//a[@class="download-file"]' );

		foreach( $imageElements as $imageElement ) {
			$data['file'] = $imageElement->getAttribute( 'href' );
		}

		$elements = $xpath->query( '//div[@class="item-description-static"]' );

		foreach( $elements as $element ) {
			$h3elements = $element->getElementsByTagName( 'h3' );

			foreach ( $h3elements as $h3element ) {
				$key = rtrim( $h3element->nodeValue, ':' );
				break;
			}

			$element->removeChild( $h3element );
			$value = $element->nodeValue;

			$data[$key] = trim( $value );
		}

		return $data;
	}

}
