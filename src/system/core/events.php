<?php
namespace System\Events;

if (!defined('SYSTEM')) exit('No direct script access allowed');

use \System\Core\Singleton;
use \System\Exceptions\KnownEventException;
use \System\Exceptions\InvalidListenerException;
use \System\Exceptions\KnownListenerException;
use \System\Exceptions\NoSuchEventException;

/**
 * Events class
 * Handles event firing
 * 
 * Needs a minimum of PHP 5.4 because of the type hint 'callable'
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @author		Rick Lubbers <me@ricklubbers.nl>
 * @since		2.0
 */
class Events extends Singleton {
	
	private $events = array();
	
	/**
	 * Adds an event if not already added
	 * @param	string	$event		The event to be registered
	 * @return	void
	 */
	public function addEvent($name) {
		if (!$this->eventRegistered($name)) {
			$this->events[$name] = array();
		} else {
			throw new KnownEventException("Event already registered");
		}
	}
	
	/**
	 * Adds listeners to an existing event
	 * @param	string		$event		Name of the event to attach the listener to
	 * @param	callable	$listener	The listener to call when the event is being triggered
	 * @param	int			$priority	Determines priority on a scale from 0 to 100
	 * @return	void
	 */
	public function addListener($event, callable $listener) {
		if (is_callable($listener)) {
			if ($this->eventRegistered($event)) {
				if (!$this->isListenerRegistered($event, $listener)) {
					$this->events[$event][] = array('function'=>$listener, 'priority'=>0);	
				} else {
					throw new KnownListenerException();
				}
			} else {
				throw new NoSuchEventException();
			}
		} else {
			throw new InvalidListenerException();
		}
	}
	
	/**
	 * Checks if a listener is already registered to an event
	 * @param	string		$event		The event to check on
	 * @param	callable	$listener	Listener to check
	 * @return	boolean
	 */
	public function isListenerRegistered($event, callable $listener) {
		if ($this->eventRegistered($event)) {
			is_callable($listener, false, $listener_str);
			foreach ($this->event[$event] as $clist) {
				$clist = $clist[0];
				is_callable($clist, false, $clist_str);
				if ($listener_str == $clist_str) {
					return true;
				}
			}
			return false;
		} else {
			throw new NoSuchEventException();
		}
	}
	
	/**
	 * Checks if an event is registered
	 * @param	string	$event		Check if this is already in the list
	 * @return	boolean
	 */
	public function eventRegistered($name) {
		return isset($this->events[$name]);
	}
	
	/**
	 * Triggers an event
	 * Returns null if event is not registered
	 * @param	string	$eventname	The name of the event to fire
	 * @param	mixed	$params		Things that get passed to the event handlers
	 * @return	mixed
	 */
	public function triggerEvent($eventname, $params) {
		if ($this->eventRegistered($eventname)) {
			foreach ($this->events[$eventname] as $listener) {
				call_user_func_array($listener, $params);
			}
		} else {
			throw new NoSuchEventException();
		}
	}
	
}
