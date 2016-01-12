<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;
use Wikibot\WikibaseClient;

class SetStatementCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'set-statement' )
			->setDescription( 'Set a statement' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'devrepo' );

		$apiClient = $this->apiClientFactory->newApiClient( $wiki );

		$wikibaseClient = new WikibaseClient( $apiClient );

		$data = json_decode( '{"type":"statement","mainsnak":{"snaktype":"value","property":"P661","datavalue":{"type":"wikibase-entityid","value":{"entity-type":"item","numeric-id":15213}}},"id":"Q888$fae29d32-47c8-f0ad-da96-32aa7d2fb222","rank":"normal"}' );

		$response = $wikibaseClient->setClaim( $data, 23443 );

		echo "post response\n";
		var_export( $response );
	}

}
