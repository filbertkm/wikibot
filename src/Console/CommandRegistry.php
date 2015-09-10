<?php

namespace Wikibot\Console;

class CommandRegistry {

	public function getCommands() {
		$classNames = array(
			'\Wikibot\Console\Commands\AddStatementCommand',
			'\Wikibot\Console\Commands\CategoryMembersCommand',
			'\Wikibot\Console\Commands\EditEntityCommand',
			'\Wikibot\Console\Commands\EditPageCommand',
			'\Wikibot\Console\Commands\PurgeCommand',
			'\Wikibot\Console\Commands\SetLabelCommand',
			'\Wikibot\Console\Commands\SetReferenceCommand',
			'\Wikibot\Console\Commands\SetStatementCommand',
			'\Wikibot\Console\Commands\UndoCommand',
			'\Wikibot\Console\Commands\Upload\FileUploadCommand',
			'\Wikibot\Console\Commands\Upload\UploadCommand'
		);

		foreach( $classNames as $className ) {
			$commands[] = $this->newCommand( $className );
		}

		return $commands;
	}

	private function newCommand( $class ) {
		$command = new $class();

		return $command;
	}

}
