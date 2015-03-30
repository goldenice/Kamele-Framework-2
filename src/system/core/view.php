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
		return 	$this->ifelse(
					$this->fillInOutputs(
						$this->fillInViews(
							file_get_contents($this->path)
						), $data
					), $data
				);
		
	}
	
	/**
	 * Removes parts of the view that won't be needed (like false if statements)
	 * 
	 * @param 	string		$raw		The raw view to process
	 * @param	string[]	$data		The data to use while checking
	 * @return	string
	 */
	private function ifelse($raw, $data) {
		$layer = 0;
		$keepif = array();
		$from = array();
		$deleteparts = array();												// Formatted [ [from, to], [from, to] ]
		
		preg_match_all('/{{.*}}/', $raw, $matches, PREG_OFFSET_CAPTURE);		// Matches every {{something}} formatted tag
		//var_dump(json_encode($matches));
		//return $raw;
		foreach ($matches[0] as $tag) {
			$tagparts = explode(':', trim($tag[0], '{}'));								// Split the parts separated by a :
			switch ($tagparts[0]) {
				case 'if':
					$layer++;
					$keepif[$layer] = ($data[$tagparts[1]] == true);
					if (!$keepif[$layer]) {
						$from[$layer] = $tag[1];
					} else {
						$deleteparts[] = array($tag[1], $tag[1] + strlen($tag[0]));
						$from[$layer] = -1;
					}
					break;
				case 'else':
					if (!$keepif[$layer]) {
						$deleteparts[] = array($from[$layer], $tag[1] + strlen($tag[0]));
					} else {
						$from[$layer] = $tag[1];
					}
					break;
				case 'endif':
					if ($keepif[$layer]) {
						if ($from[$layer] == -1) $from[$layer] = $tag[1];
						$deleteparts[] = array($from[$layer], $tag[1] + strlen($tag[0]));
					} else {
						$deleteparts[] = array($tag[1], $tag[1] + strlen($tag[0]));
					}
					unset($keepif[$layer]);
					unset($from[$layer]);
					$layer--;
					break;
				default:
					break;
			}
		}

		while (($deleteparts != null) && ($elem = array_pop($deleteparts)) != null) {
			$raw = substr($raw, 0, $elem[0]) . substr($raw, $elem[1]);
			foreach ($deleteparts as $key=>$val) {
				$val[0] = min($val[0], $elem[0]);
				$val[1] = min($val[1], $elem[0]);
				if ($val[0] != $val[1]) {
					$deleteparts[$key] = $val;
				} else {
					unset($deleteparts[$key]);
				}
			}
		}
		
		return $raw;
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
	 * @param	string		$view
	 * @param	string[]	$data
	 * @return	string
	 */
	private function fillInOutputs($view, $data) {
		$output = '';
		$data['baseurl'] = BASEURL;
		$parts = explode('{{output:', $view);
		$output = $parts[0];
		for ($i = 1; $i < sizeof($parts); $i++) {
			$part = $parts[$i];
			$subparts = explode('}}', $part);
			if (sizeof($foreach = explode(':', $subparts[0])) > 1) {
			    if (!isset($data[$foreach[0]])) {       // Substitute original if no data given
			        $output .= '{{output:'.$part;
			    } else {
				    $output .= $this->foreachView($data[$foreach[0]], $foreach[1]);
				    unset($subparts[0]);
				    $output .= implode('}}', $subparts);
			    }
			} else {
			    if (!isset($data[$subparts[0]])) {      // Substitute original if no data given
			        $output .= '{{output:'.$part;
			    } else {
				    $output .= $data[$subparts[0]];
				    unset($subparts[0]);
				    $output .= implode('}}', $subparts);
			    }
			}
		}
		return $output;
	}
	
	/**
	 * Foreach
	 * 
	 * @param	string		$viewpath
	 * @param	string[]	$data
	 * @return	string
	 */
	private function foreachView($viewpath, $data) {
		$output = '';
		foreach ($data as $value) {
			if (!is_array($value)) $value = array('value'=>$value);
			$view = new View($viewpath);
			$output .= $view->render($value);
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
			$view = new View($subparts[0]);
			$output .= $view->render(array());
			unset($subparts[0]);
			$output .= implode('}}', $subparts);
		}
		return $output;
	}
	
}