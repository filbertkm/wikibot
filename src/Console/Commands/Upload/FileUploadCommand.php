<?php

namespace Wikibot\Console\Commands\Upload;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\Upload\CommonsUploader;
use Wikibot\Upload\NARAClintonScraper;
use Wikibot\Upload\TemplateBuilder;

class FileUploadCommand extends Command {

	protected function configure() {
		$this->setName( 'file-upload' )
			->setDescription( 'Upload a file' )
			->addArgument(
				'file',
				InputArgument::REQUIRED,
				'Filename'
			);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		set_time_limit( 0 );

		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'commonswiki' );

		$helper = $this->getHelper( 'question' );

		$httpClient = new HttpClient( 'Wikibot' );

		$filename = $input->getArgument( 'file' );

		$data = array(
			'file' => $filename,
			'author' => '[[User:Aude|Aude]]',
			'source' => '{{own}}'
		);

		$templateBuilder = new TemplateBuilder( $filename );

		$title = '';

		$question = new Question( 'Provide a title: ' );
		$title .= $helper->ask( $input, $output, $question );

		$question = new Question( 'Provide a description: ' );
		$data['description'] = $helper->ask( $input, $output, $question );

		foreach( $data as $field => $value ) {
			if ( $field === 'Title' ) {
				$title = str_replace( ' ', '_', $value );
			} elseif ( $field === 'file' ) {
				$filename = $value;
			} elseif ( $field === 'description' ) {
				$value = "{{en|1=$value}}";
				$templateBuilder->setField( 'Description', $value );
			} elseif ( $field === 'source' ) {
				$templateBuilder->setField( 'Source', $value );
			} elseif ( $field === 'author' ) {
				$templateBuilder->setField( 'Author', $value );
			} elseif ( $field === 'date' ) {
				$templateBuilder->setField( 'Date', $value );
			}
		}

		$template = $templateBuilder->getTemplate();

		$text = "== {{int:filedesc}} ==\n"
			. "$template\n\n"
			//. "{{Location dec|40.71302|-74.01302}}\n\n"
			. "== {{int:license-header}} ==\n"
			. "{{self|cc-by-sa-3.0}}";
			// . "[[Category:One World Observatory]]";

		$apiClient = new ApiClient( $httpClient, $wiki );
		$apiClient->login();

		$uploader = new CommonsUploader( $apiClient );
		$result = $uploader->upload( $title, $filename, $text );

		$output->writeln( "done" );
	}

}
