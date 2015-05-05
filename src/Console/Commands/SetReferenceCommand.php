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

class SetReferenceCommand extends Command {

	protected function configure() {
		$this->setName( 'set-reference' )
			->setDescription( 'Set a reference' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'devrepo' );

		$apiClient = new ApiClient(
			new HttpClient( 'Wikibot' ),
			$wiki
		);

		$wikibaseClient = new WikibaseClient( $apiClient );

		$statement = 'Q888$fae29d32-47c8-f0ad-da96-32aa7d2fb222';
		$refSnaks = json_decode( '{"P1089":[{"snaktype":"value","property":"P1089","datavalue":{"type":"wikibase-entityid","value":{"entity-type":"item","numeric-id":15213}}}]}' );
		$baseRev = 23442;

		$response = $wikibaseClient->setReference( $statement, $refSnaks, $baseRev );

		echo "post response\n";
		var_export( $response );
	}

}
