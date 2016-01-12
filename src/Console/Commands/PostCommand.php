<?php

namespace Wikibot\Console\Commands;

use Filbertkm\Http\HttpClient;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostCommand extends Command {

	protected function configure() {
		$this->setName( 'post' )
			->setDescription( 'Make a post request' )
			->addArgument(
				'url',
				InputArgument::REQUIRED,
				'Url'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$httpClient = new HttpClient( 'Wikibot', 'wikibot' );
		$url = $input->getArgument( 'url' );

		$res = $httpClient->post( $url );

		var_export( $res );
	}

}
