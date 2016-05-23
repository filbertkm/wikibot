<?php

namespace Wikibot\Console\Commands\Api;

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
			->setDescription( 'Set a label' )
			->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki ID'
			)
			->addArgument(
				'id',
				InputArgument::REQUIRED,
				'Entity ID'
			)
			->addArgument(
				'lang',
				InputArgument::REQUIRED,
				'Language code'
			)
			->addArgument(
				'label',
				InputArgument::REQUIRED,
				'Label'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$apiClient = $this->apiClientFactory->newApiClient(
			$input->getArgument( 'wiki' )
		);

		$apiClient->login();

		$id = $input->getArgument( 'id' );

		$params = array(
			'action' => 'wbgetentities',
			'ids' => $id
		);

		$response = $apiClient->get( $params );

		if ( !isset( $response['entities'][$id]['lastrevid'] ) ) {
			$output->writeln( 'ERROR: Entity not found' );
			$apiClient->logout();

			return;
		}

		$params = array(
			'action' => 'wbsetlabel',
			'id' => $id,
			'value' => $input->getArgument( 'label' ),
			'language' => $input->getArgument( 'lang' ),
			'baserevid' => $response['entities'][$id]['lastrevid']
		);

		$response = $apiClient->post( $params );

		$apiClient->logout();

		var_export( $response );
	}

}
