<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\WikibaseClient;
use Wikibot\Wikibase\ApiEntityLookup;

class AddStatementCommand extends Command {

	protected function configure() {
		$this->setName( 'add-statement' )
			->setDescription( 'Add a statement' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'devrepo' );

		$apiClient = new ApiClient(
			new HttpClient( 'Wikibot' ),
			$wiki
		);

		$wikibaseClient = new WikibaseClient( $apiClient );

		$entityId = 'Q888';
		$propertyId = 'P853';
		$value = json_decode( '{"entity-type":"item","numeric-id":4903}' );

		$response = $wikibaseClient->createClaim( $entityId, $propertyId, $value );

		echo "post response\n";
		var_export( $response );
	}

}
