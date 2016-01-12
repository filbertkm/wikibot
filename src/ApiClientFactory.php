<?php

namespace Wikibot;

use Filbertkm\Http\HttpClient;
use Wikimedia\Assert\Assert;

class ApiClientFactory {

	/**
	 * @var HttpClient
	 */
	private $httpClient;

	public function __construct( HttpClient $httpClient ) {
		$this->httpClient = $httpClient;
	}

	public function newApiClient( $wikiId ) {
		Assert::parameterType( 'string', $wikiId, '$wikiId' );

		return new ApiClient(
			$this->httpClient,
			$wikiId
		);
	}

}
