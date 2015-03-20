<?php
namespace System\Cli;

class Console {
	
	private $running = true;
	
	/**
	 * The main loop
	 * Handles user input
	 */
	public function main() {
		while ($this->running) {
			$input = $this->readline();
		}
	}
	
	/**
	 * A replacement for the built-in readline() function, which is available only on linux systems
	 * This function is compatible with windows as well
	 * @return	string
	 */
	private function readline() {
		if (PHP_OS == 'WINNT') {
			echo 'Kamele ' . KAMELE_VERSION . ' $ ';
			flush();
			$line = stream_get_line(STDIN, 1024, PHP_EOL);
		} else {
			$line = readline('Kamele ' . KAMELE_VERSION . ' $ ');
		}
		return $line;
	}
	
	/**
	 * Parses a command
	 * @param	string		$input		The command to parse
	 * @return 	void
	 */
	private function parse($input) {
		// TODO: implement	
	}
}