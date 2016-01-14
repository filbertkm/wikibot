<?php

namespace Wikibot;

use Filbertkm\Http\HttpClient;
use MediaWiki\Sites\Lookup\SiteLookup;
use Wikimedia\Assert\Assert;

class ApiClientFactory {

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

	public function __construct( HttpClient $httpClient, SiteLookup $siteLookup, $users ) {
		$this->httpClient = $httpClient;
		$this->siteLookup = $siteLookup;
		$this->users = $users;
	}

	public function newApiClient( $siteId ) {
		Assert::parameterType( 'string', $siteId, '$siteId' );

		return new ApiClient(
			$this->httpClient,
			$this->siteLookup,
			$this->users,
			$siteId
		);
	}

}
