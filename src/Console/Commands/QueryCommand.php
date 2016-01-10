<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\Wikibase\ApiEntityLookup;

class QueryCommand extends Command {

	protected function configure() {
		$this->setName( 'query' )
			->setDescription( 'Wikidata query' )
			->addArgument(
				'instance-of',
				InputArgument::REQUIRED,
				'Instance of'
			)
            ->addArgument(
				'located-in',
				InputArgument::REQUIRED,
				'Located in'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();

	}

}
