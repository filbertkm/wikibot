<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\MediaWiki\Page;
use Wikibot\Query\QueryRunner;

class QueryCommand extends Command {

	private $queryRunner;

	protected function configure() {
		$this->setName( 'query' )
			->setDescription( 'Query Wikidata' )
			->addArgument(
				'params',
				InputArgument::REQUIRED,
				'Params'
			);
	}

	public function setServices( QueryRunner $queryRunner ) {
		$this->queryRunner = $queryRunner;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		list( $propertyId, $valueId ) = explode( ':', $input->getArgument( 'params' ) );

		$ids = $this->queryRunner->getPropertyEntityIdValueMatches( $propertyId, $valueId );

		var_export( $ids );
	}

}
