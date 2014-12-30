<?php
namespace System\Core;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * View class
 * 
 * @package		Kamele
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
class View {
	
	/**
	 * @var		string
	 */
	private $path;
	
	/**
	 * Constructor
	 * 
	 * @param	string	$path
	 * @return	void
	 */
	public function __construct($path) {
		if (file_exists($path) && is_file($path)) {
			$this->path = $path;
		} else {
			$this->path = null;
		}
	}
	
	/**
	 * Renders the view and returns it
	 * 
	 * @param	string[]	$data
	 * @return	string
	 */
	public function render($data) {
		$view = $this->fillInOutputs($data, file_get_contents($this->path));
		return $this->fillInViews($view);
	}
	
	/**
	 * Renders the view and outputs it
	 * 
	 * @param	string[]	$data
	 * @return	void
	 */
	public function output($data) {
		echo $this->render($data);
	}
	
	/**
	 * Fills in data
	 * 
	 * @param	string[]	$data
	 * @param	string		$view
	 * @return	string
	 */
	private function fillInOutputs($data, $view) {
		$output = '';
		$data['baseurl'] = BASEURL;
		$parts = explode('{{output:', $view);
		$output = $parts[0];
		for ($i = 1; $i < sizeof($parts); $i++) {
			$part = $parts[$i];
			$subparts = explode('}}', $part);
			if (sizeof($foreach = explode(':', $subparts)) > 1) {
				$output .= $this->foreachView($data[$foreach[0]], $foreach[1]);
			} else {
				$output .= $data[$subparts[0]];
				unset($subparts[0]);
				$output .= implode('}}', $subparts);
			}
		}
		return $output;
	}
	
	/**
	 * Foreach
	 * 
	 * @param	string[]	$data
	 * @return	string
	 */
	private function foreachView($data, $viewpath) {
		$output = '';
		foreach ($data as $value) {
			if (!is_array($value)) $value = array($value);
			$output .= (new View($viewpath))->render($value);
		}
		return $output;
	}
	
	/**
	 * Fills in other views
	 * 
	 * @param	string		$view
	 * @return	string
	 */
	private function fillInViews($view) {
		$output = '';
		$data['baseurl'] = BASEURL;
		$parts = explode('{{view:', $view);
		$output = $parts[0];
		for ($i = 1; $i < sizeof($parts); $i++) {
			$part = $parts[$i];
			$subparts = explode('}}', $part);
			$output .= (new View($subparts[0]))->render();
			unset($subparts[0]);
			$output .= implode('}}', $subparts);
		}
		return $output;
	}
	
}