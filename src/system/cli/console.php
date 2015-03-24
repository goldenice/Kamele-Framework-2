<?php
namespace System\Cli;

use \System\Core\Singleton;

class Console extends Singleton {
	
	private $running = true;
	private $cmds = array(
			'help' 	=> array(
				'class' 		=> '\System\Cli\Console',
				'method' 		=> 'renderHelp',
				'description' 	=> 'Displays help info; a short description of all commands'
			),
			'do' 	=> array (
				'class'			=> '\System\Cli\Console',
				'method'		=> 'execute',
				'description'	=> 'Execute a given class and method statically'
			),
			'quit'	=> array (
				'class'			=> '\System\Cli\Console',
				'method'		=> 'quit',
				'description'	=> 'Quitter...'
			)
			
		);
	
	/**
	 * The main loop
	 * Handles user input
	 */
	public function main() {
		while ($this->running) {
			$input = $this->readline();
			if ($input != false) {
				$parts = $this->parse($input);
				if (isset($this->cmds[$parts[0]])) {
					$this->addToHistory($input);
					$this->output(call_user_func(array($this->cmds[$parts[0]]['class'], $this->cmds[$parts[0]]['method']), array_shift($parts)));
				} else {
					$this->output('Command not recognized');
				}
			} else {
				$this->running = false;
			}
		}
	}
	
	/**
	 * Adds a line to the history when readline is being used
	 * @param	string		$command		Command to add to history
	 */
	private function addToHistory($command) {
		if (function_exists("readline_add_history")) {
			\readline_add_history($command);
		}
	}
	
	/**
	 * Outputs a certain string to the STDOUT
	 * @param	string		$string 		The string to output
	 * @return	void
	 */
	private function output($string) {
		fputs(STDOUT, $string."\n");
	}
	
	/**
	 * A replacement for the built-in readline() function, which is available only on linux systems
	 * This function is compatible with windows as well
	 * @return	string
	 */
	private function readline() {
		$prompt = "Kamele " . KAMELE_VERSION . " $ ";
		if (function_exists("readline")) {
			return \readline($prompt);
		} else {
			if($prompt){
				fputs(STDOUT, $prompt);
			}
			flush();
			$line = rtrim(fgets(STDIN, 1024));
			return $line;
		}
	}
	
	/**
	 * Parses a command into parts
	 * @param	string		$input		The command to parse
	 * @return 	array
	 */
	private function parse($input) {
		$output = array();
		$open = false;
		$buffer = "";
		$parts = explode(" ", $input);
		foreach ($parts as $part) {
			if (substr($part, 0, 1) == '"' && !$open) {
				$open = true;
				$part = substr($part, 1);
			}
			if ($open) {
				if (substr($part, strlen($part)-1, 1) == '"') {
					$part = substr($part, 0, strlen($part)-1);
					$buffer .= " " . $part;
					$output[] = trim($buffer);
					$buffer = "";
					$open = false;
				} else {
					$buffer .= " " . $part;
				}
			} else {
				$output[] = trim($part);
			}
		}
		return $output;
	}
	
	/**
	 * Sets running to false
	 */
	public function stopRunning() {
		$this->running = false;
	}
	
	/**
	 * Returns the list of commands
	 * @return 	array
	 */
	public function getCommands() {
		return $this->cmds;
	}
	
	/**
	 * Executes the help command
	 * @param	string[]	$args		The arguments given with the command
	 * @return 	string
	 */
	public static function renderHelp($args) {
		$output = "Kamele Framework " . KAMELE_VERSION . "\n";
		$output .= "Inside of Kamele Framework interactive CLI mode you have these commands at your disposal:\n";
		foreach (self::getInstance()->getCommands() as $key=>$cmd) {
			$output .= str_pad($key, 24, " ") . $cmd['description'] . "\n";
		}
		return $output;
	}
	
	/**
	 * Runs a specific method statically
	 * @param	string[]	$args		Arguments
	 * @return	string
	 */
	public static function execute($args) {
		// TODO: implement
	}
	
	/**
	 * Quitting
	 * @param	string[]	$args		Arguments
	 * @return	string
	 */
	public static function quit() {
		self::getInstance()->stopRunning();
	}
}