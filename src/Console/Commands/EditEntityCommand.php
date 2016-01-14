<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class EditEntityCommand extends Command {

	/**
	 * @var ApiClientFactory
	 */
	private $apiClientFactory;

	/**
	 * @param ApiClientFactory $apiClientFactory
	 */
	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'edit-entity' )
			->setDescription( 'Edit entity' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient( 'devrepo' );

		$params = array(
			'action' => 'wbeditentity',
			'id' => 'Q16605',
			'data' => '{"claims":[{"mainsnak":{"snaktype":"value","property":"P1052","datavalue":{"value":"ExampleString","type":"string"}},"type":"statement","rank":"normal"}]}',
			'baserevid' => 23502
		);

		$apiClient->login();
		$response = $apiClient->post( $params );

		echo "post response\n";
		var_export( $response );
	}

}
