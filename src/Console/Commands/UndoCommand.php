<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\Wikibase\ApiEntityLookup;

class UndoCommand extends Command {

	protected function configure() {
		$this->setName( 'undo' )
			->setDescription( 'Undo a revision' )
            ->addArgument(
                'title',
                InputArgument::REQUIRED,
                'Title'
            )
			->addArgument(
				'revision',
				InputArgument::REQUIRED,
				'Revision'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'devrepo' );

		$apiClient = new ApiClient(
			new HttpClient( 'Wikibot' ),
			$wiki
		);

		$params = array(
			'action' => 'edit',
			'title' => $input->getArgument( 'title' ),
			'undo' => $input->getArgument( 'revision' ),
			'text' => 'ooomg'
		);

		$apiClient->login();
		$response = $apiClient->post( $params );

		echo "post response\n";
		var_export( $response );
	}

}
