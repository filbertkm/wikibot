<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class SetLabelCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'set-label' )
			->setDescription( 'Set a label' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'wikidatawiki' );

		$apiClient = $this->apiClientFactory->newApiClient( $wiki );

		$params = array(
			'action' => 'wbsetlabel',
			'id' => 'P357',
			'value' => 'título',
			'language' => 'es',
			'baserevid' => 222076986,
			'summary' => 'debugging T102148'
		);

		$apiClient->login();
		$response = $apiClient->post( $params );

		echo "post response\n";
		var_export( $response );
	}

}
