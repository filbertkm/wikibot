<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class PurgeCommand extends Command {

	private $apiClientFactory;

	public function setServices( ApiClientFactory $apiClientFactory ) {
		$this->apiClientFactory = $apiClientFactory;
	}

	protected function configure() {
		$this->setName( 'purge' )
			->setDescription( 'Purge a page' )
			->addArgument(
				'start',
				InputArgument::REQUIRED,
				'Start'
			)
			->addArgument(
				'end',
				InputArgument::REQUIRED,
				'End'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'wikidatawiki' );

		$apiClient = $this->apiClientFactory->newApiClient( $wiki );

		$ids = array();

		$start = intval( ltrim( $input->getArgument( 'start' ), 'Q' ) );
		$end = intval( ltrim( $input->getArgument( 'end' ), 'Q' ) );

		foreach ( range( $start, $end ) as $id ) {
			$ids[] = "Q$id";
		}

		$batches = array_chunk( $ids, 500 );

		foreach ( $batches as $batch ) {
			$chunks = array_chunk( $batch, 25 );

			foreach ( $chunks as $chunk ) {
				$params = array(
					'action' => 'purge',
					'titles' => implode( '|', $chunk ),
					'forcelinkupdate' => 1
				);

				$apiClient->login();
				$apiClient->post( $params );

				$output->writeln( "Processed up to " . end( $chunk ) );
				$app['monolog']->addDebug( "Processed up to " . end( $chunk ) );

				sleep( 1 );
			}

			sleep( 5 );
		}
	}

}
