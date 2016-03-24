<?php

namespace Wikibot\Console\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\Query\QueryCsvPrinter;
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
			)
			->addOption(
				'output',
				null,
				InputOption::VALUE_REQUIRED,
				'Output file'
			);
	}

	public function setServices( QueryRunner $queryRunner ) {
		$this->queryRunner = $queryRunner;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$pairs = explode( ',', $input->getArgument( 'params' ) );
		$result = $this->queryRunner->getPropertyEntityIdValueMultiMatches( $pairs );

		$queryPrinter = new QueryCsvPrinter();
		$results = $queryPrinter->output( $result );

		if ( $outfile = $input->getOption( 'output' ) ) {
			file_put_contents( $outfile, $results );
		} else {
			echo $results . "\n";
		}

	}

}
