<?php
namespace System\Database\Drivers\Mysqli;

if (!defined('SYSTEM')) exit('No direct script access allowed');

use \System\Database\DatabaseDriver;

/**
 * Querybuilder implementation for MySQL
 * 
 * @package     Kamele Framework
 * @subpackage  System
 * @since       2.0
 * @author      Rick Lubbers <me@ricklubbers.nl>
 */
class QueryBuilder implements \System\Database\QueryBuilder {
    
    private $initialpart = "";
    
    private $from = array();
    private $orderby = array();
    private $limit = '';
    private $where = '';
    
    private $driver;
    
    public function __construct($driver) {
        $this->driver = $driver;
    }
    
    /**
     * Executes a query on the database
     * @return  Result
     */
    public function execute() {
        $this->driver->query($this->getQuery());
    }
    
    /**
     * Returns the query as a string
     * @return  string
     */
    public function getQuery() {
        if ($this->initialpart == "") return "";
        $query = $this->initialpart;
        $query .= $this->renderFrom();
        $query .= $this->renderOrderBy();
        $query .= $this->limit;
        $query .= $this->where;
        return $query;
    }
    
    /**
     * Returns FROM clause as a string
     * @return  string
     */
    protected function renderFrom() {
        $output = "FROM ";
        $first = true;
        if (count($this->from) > 0) {
            foreach ($this->from as $key => $value) {
                $output .= ($first == false) ? ", " : "";
                if (!is_numeric($key)) {
                    $output .= $this->backtick($key) . " AS " . $this->backtick($value);
                } else {
                    $output .= $this->backtick($value);
                }
                $first = false;
            }
            return $output . " ";
        }
        return "";
    }
    
    /**
     * Returns ORDER BY clause as a string
     * @return  string
     */
    protected function renderOrderBy() {
        $output = "ORDER BY ";
        $first = true;
        if (count($this->orderby) > 0) {
            foreach ($this->orderby as $key => $value) {
                $output .= ($first == false) ? ", " : "";
                $output .= $this->backtick($value[0]) . " " . $this->ascOrDesc($value[1]);
                $first = false;
            }
            return $output . " ";
        }
        return "";
    }
    
    /**
     * Registers a SELECT clause as main query
     * @param   string|array    $parts
     * @return  QueryBuilder
     */
    public function select($parts) {
        $output = "SELECT ";
        if (is_array($parts)) {
            $first = true;
            foreach ($parts as $key => $value) {
                $output .= ($first == false) ? ", " : "";
                // TODO: fix that ugly zero check in this if
                if (($key == 'MAX' || $key == 'COUNT' || $key == 'AVG' || $key == 'MIN') && $key != 0) {        // Why the fuck is that zero screwing things up?!
                    $output .= $key . "(" . $this->backtick($value) . ")";
                } else if (!is_numeric($key)) {
                    $output .= $this->backtick($key) . " AS " . $this->backtick($value);
                } else {
                    $output .= $this->backtick($value);
                }
                $first = false;
            }
        } else {
            if ($parts != '*') {
                $output .= $this->backtick($parts);
            } else {
                $output .= '*';
            }
        }
        $this->initialpart = $output . " ";
        return $this;
    }
    
    /**
     * Adds from parts
     * @param   array   $input
     * @return  QueryBuilder
     */
    public function from($input) {
        if (is_array($input)) {
            $this->from = array_merge($this->from, $input);
        } else {
            $this->from[] = $input;
        }
        return $this;
    }
    
    /**
     * Adds order by clauses
     * @param   string|array    $input
     * @return  QueryBuilder
     */
    public function orderBy($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                if (!is_numeric($key)) {
                    $this->orderby[] = array($key, $this->ascOrDesc($value));
                } else {
                    $this->orderby[] = array($value, 'ASC');
                }
            }
        } else {
            $this->orderby[] = array($input, 'ASC');
        }
        return $this;
    }
    
    /**
     * Adds (AND) where clause
     * @param	string|array		$input
     * @return	QueryBuilder
     */
    public function where($input) {
    	if ($this->isMultiArray($input)) {
    		foreach ($input as $key => $value) {
    			if (!is_numeric($value)) $value = $this->quote($value);
    			$this->appendWhere($this->backtick($key) . "=" . $value);
    		}
    	}
    	else if (is_array($input) && sizeof($input) == 2) {
    		if (!is_numeric($input[1])) $input[1] = $this->quote($input[1]);
    		$this->appendWhere($this->backtick($input[0]) . "=" . $input[1]);
    	} else {
    		$this->appendWhere((string) $input);
    	}
    }
    
    /**
     * Append to the WHERE string
     * @param	string		$input
     * @return	void
     */
    protected function appendWhere($input) {
    	if (trim($this->where) == "") {
    		$this->where = "WHERE" . $input;
    	} else {
    		$this->where .= "AND " . $input . " ";
    	}
    }
    
    /**
	 * Adds limit by clauses
	 * @param	string|array		$input
	 * @return	QueryBuilder
	 */
	public function limit($input) {
		if (is_array($input)) {
			if (sizeof($input) == 2) {
				$this->limit = "LIMIT ".$input[0].",".$input[1]." ";
			} else {
				// TODO: throw some kind of exception
			}
		} else {
			if (is_numeric($input)) {
				$this->limit = "LIMIT ".$input." ";
			} else {
				// TODO: throw some kind of exception
			}
		}
		return $this;
	}
    
    public function update($table, array $keyvalpairs) {}
	public function insert() {}
	public function delete() {}
	
	
	
	
	/**
	 * Checks if a given array is multidimensional
	 * @param   array   $a
	 * @return  boolean
	 */
	public function isMultiArray($a){
        foreach($a as $v) if(is_array($v)) return true;
        return false;
    }
	
    /**
     * Returns an ASC string or DESC string depending on the input
     * Always evaluates to one of these two
     * @param   string  $input
     * @return  string
     */
    protected function ascOrDesc($input) {
        return (strtoupper($input) == 'DESC') ? 'DESC' : 'ASC';
    }
    
    /**
     * Puts backticks around string
     * @param	string	$input
     * @return	string
     */
    protected function backtick($input) {
    	return "`".$input."`";
    }
    
    /**
     * Puts single quotes around string
     * @param	string	$input
     * @return	string
     */
    protected function quote($input) {
    	return "'".$input."'";
    }
}