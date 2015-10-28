<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\Wikibase\ApiEntityLookup;

class CategoryMembersCommand extends Command {

	protected function configure() {
		$this->setName( 'cat-members' )
			->setDescription( 'Category members' )
            ->addArgument(
				'wiki',
				InputArgument::REQUIRED,
				'Wiki'
			)
			->addArgument(
                'category',
                InputArgument::REQUIRED,
                'Category'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		echo __METHOD__;
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( $input->getArgument( 'wiki' ) );

		$apiClient = new ApiClient(
			new HttpClient( 'Wikibot' ),
			$wiki
		);

		$apiClient->login();

		$params = array(
			'action' => 'query',
			'cmtitle' => 'Category:' . $input->getArgument( 'category' ),
			'list' => 'categorymembers',
			'cmlimit' => 500
		);

		$response = $apiClient->get( $params );

		$pages = $response['query']['categorymembers'];
		$pageIds = array();

		foreach( $pages as $page ) {
			$pageIds[] = $page['pageid'];
		}
	}

}
