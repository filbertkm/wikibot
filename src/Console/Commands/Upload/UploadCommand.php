<?php

namespace Wikibot\Console\Commands\Upload;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibot\ApiClient;
use Wikibot\HttpClient;
use Wikibot\Upload\CommonsUploader;
use Wikibot\Upload\NARAClintonScraper;
use Wikibot\Upload\TemplateBuilder;

class UploadCommand extends Command {

	protected function configure() {
		$this->setName( 'upload' )
			->setDescription( 'Upload a file' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$app = $this->getSilexApplication();
		$wiki = $app['app-config']->getWiki( 'commonswiki' );

		$httpClient = new HttpClient( 'Wikibot' );

		$baseUrl = 'http://clinton.presidentiallibraries.us/items/show/';
		$scraper = new NARAClintonScraper( $httpClient, $baseUrl );

		$itemId = 16163;
		$data = $scraper->getItemData( $itemId );

		$templateBuilder = new TemplateBuilder();

		$title = 'Clinton Presidential Library - ';
		$sourceUrl = $baseUrl . $itemId;

		foreach( $data as $field => $value ) {
			if ( $field === 'Title' ) {
				$title = $value;
			} elseif ( $field === 'file' ) {
				$file = $value;
			} elseif ( $field === 'Description' ) {
				$value = "{{en|1=$value}}";
				$templateBuilder->setField( 'Description', $value );
			} elseif ( $field === 'Publisher' ) {
				$templateBuilder->setField( 'Source', $value . " [$sourceUrl]" );
			} elseif ( $field === 'Creator(s)' ) {
				$templateBuilder->setField( 'Author', $value );
			} elseif ( $field === 'Date' ) {
				$templateBuilder->setField( 'Date', $value );
			}
		}

		$filename = '/tmp/' . str_replace( ' ', '_', $title ) . '.jpg';
		file_put_contents( $filename, file_get_contents( $file ) );

		$template = $templateBuilder->getTemplate();

		$text = "== {{int:filedesc}} ==\n"
			. "$template\n\n"
			. "== {{int:license-header}} ==\n"
			. "{{PD-USGov}}\n\n"
			. "[[Category:Buddy (dog)]]";

		$apiClient = new ApiClient( $httpClient, $wiki );
		$apiClient->login();

		$uploader = new CommonsUploader( $apiClient );
		$result = $uploader->upload( $title, $filename, $text );

		var_export( $result );
	}

}
