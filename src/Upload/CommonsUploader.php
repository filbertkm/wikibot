<?php

namespace Wikibot\Upload;

use Wikibot\ApiClient;
use Wikibot\Api\Response;

class CommonsUploader {

	private $apiClient;

	public function __construct( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
	}

	/**
	 * @param string $fileTitle Title of the file page
	 * @param string $filePath Path to the file
	 * @param string $filePageText Text for the file page
	 *
	 * @throws UploadException
	 * @return array
	 */
	public function upload( $fileTitle, $filePath, $filePageText ) {
		$params = array(
			'action' => 'upload',
			'file' => "@$filePath",
			'text' => $filePageText,
			'filename' => $fileTitle
		);

		$res = $this->apiClient->upload( $params );

		if ( !is_array( $res ) ) {
			throw new UploadException( 'bad-response', 'Bad response from during upload' );
		}

		return $this->handleResponse( $res );
	}

	private function handleResponse( array $data ) {
		if ( isset( $data['error'] ) ) {
			$this->handleError( $data['error'] );
		} elseif ( $data['upload']['result'] === 'Warning' ) {
			$this->handleWarnings( $data['upload']['warnings'] );
		}

		$response = Response::newFromData( $data );

		return $response->getData();
	}

	private function handleError( array $data ) {
		switch( $data['code'] ) {
			case 'filetype-banned':
				throw new UploadFileTypeDisallowedException(
					$data['filetype'],
					$data['allowed'],
					$data['code'],
					$data['info']
				);
			default:
				throw new UploadException(
					$data['code'],
					$data['info']
				);
		}
	}

	private function handleWarnings( array $warnings ) {
		foreach ( $warnings as $key => $warning ) {
			if ( $key === 'exists' ) {
				throw new UploadExistsException(
					'upload-' . $key,
					'File already exists'
				);
			}
		}

		throw new UploadWarningException( $warnings, 'upload-warning', 'Upload warning' );
	}

}
