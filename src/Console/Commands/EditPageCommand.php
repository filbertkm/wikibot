<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\Wikibase\ApiEntityLookup;

class EditPageCommand extends Command {

	protected function configure() {
		$this->setName( 'edit' )
			->setDescription( 'Edit pages' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		for ( $i = 0; $i < 10; $i++ ) {
			$this->addPages();
			$output->writeln( "added pages up to $i" );
		}
	}

	private function addPages() {
		$app = $this->getSilexApplication();

		$repoClient = new ApiClient(
			new HttpClient( 'Wikibot' ),
			$app['app-config']->getWiki( 'devrepo' )
		);

		$res = $repoClient->get( array(
			'action' => 'query',
			'list' => 'random',
			'rnnamespace' => 0,
			'rnlimit' => 20
		) );

		$items = array();

		foreach( $res['query']['random'] as $page ) {
			$items[] = $page['title'];
		}

		$res = $repoClient->get( array(
			'action' => 'wbgetentities',
			'ids' => implode( '|', $items ),
			'props' => 'sitelinks'
		) );

		foreach( $res['entities'] as $id => $data ) {
			if ( array_key_exists( 'enwiki', $data['sitelinks'] ) ) {
				$apiClient = new ApiClient(
					new HttpClient( 'Wikibot' ),
					$app['app-config']->getWiki( 'devclient' )
				);

				$title = $data['sitelinks']['enwiki']['title'];

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