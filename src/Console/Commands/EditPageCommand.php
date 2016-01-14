<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClientFactory;

class EditPageCommand extends Command {

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
		$this->setName( 'edit' )
			->setDescription( 'Edit pages' )
			->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		for ( $i = 0; $i < 50; $i++ ) {
			$this->addPagesFromWiki( $input->getArgument( 'wiki' ) );
			$output->writeln( "added pages up to $i" );
		}
	}

	private function addPagesFromWiki( $wiki ) {
		$repoClient = $this->apiClientFactory->newApiClient( 'devrepo' );

		$res = $repoClient->get( array(
			'action' => 'query',
			'list' => 'random',
			'rnnamespace' => 0,
			'rnlimit' => 20
		) );

		$items = array();

		foreach ( $res['query']['random'] as $page ) {
			$items[] = $page['title'];
		}

		$res = $repoClient->get( array(
			'action' => 'wbgetentities',
			'ids' => implode( '|', $items ),
			'props' => 'sitelinks'
		) );

		foreach ( $res['entities'] as $id => $data ) {
			if ( array_key_exists( $wiki, $data['sitelinks'] ) ) {
				$apiClient = $this->apiClientFactory->newApiClient( $wiki );

				$title = $data['sitelinks'][$wiki]['title'];

				$params = array(
					'action' => 'edit',
					'title' => $title,
					'text' => 'ooomg',
					'createonly' => 1
				);

				$apiClient->login();
				$response = $apiClient->post( $params );
			}
		}
	}

}
