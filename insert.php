<?php

namespace SQL;

class Insert extends SQLQuery {

	private $table;
	private $ignore = false;
	private $columns = array();
	private $data = array();

	/**
	 * @param String $table Table to select from
	 */
	function __construct($table){
		$this->table = $table;
	}

	/**
	 * @param String $column Column to insert into
	 *
	 * @return $this
	 */
	function column($column){
		$this->columns[$column] = $column;
		return $this;
	}

	/**
	 * @param boolean $ignore Set whether to ignore duplicates
	 *
	 * @return $this
	 */
	function ignore($ignore){
		$this->ignore = $ignore;
		return $this;
	}

	/**
	 * @param Array $data Data set to insert
	 *
	 * @return $this
	 */
	function insert($data){
		array_push($this->data, $data);
		return $this;
	}

	function buildQuery(){
		$bindData = array();
		$query = "INSERT ";

		if($this->ignore){
			$query .= "IGNORE ";
		}

		$query .= "INTO `".$this->table."` (";

		if(is_array($this->columns) && count($this->columns) > 0){
			$first = true;
			foreach($this->columns as $k => $v){
				if(!$first){
					$query .= ", ";
				}

				$first = false;

				$query .= "`".$v."`";
			}
		}else{
			xdebug_print_function_stack("No columns set to insert into!");
			var_dump($this);
			die();
		}

		$query .= ") VALUES";

		if(is_array($this->data) && count($this->data) > 0){
			$first = true;
			foreach($this->data as $k => $v){
				if(!$first){
					$query .= ", ";
				}

				$first = false;

				$query .= " (?";
				$query .= str_repeat(", ?", count($v) - 1);
				$query .= ")";

                $bindData = array_merge($bindData, $v);
			}
		}else{
			xdebug_print_function_stack("No data set to insert!");
			var_dump($this);
			die();
		}

		return array($query, PDOHelper::flatten($bindData));
	}

}