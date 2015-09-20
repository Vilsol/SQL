<?php

namespace SQL;

class Delete extends SQLQuery {

	private $where;
	private $table;
	private $ignore = false;
	private $offset = 0;
	private $count = 0;
	private $by;
	private $direction;

	/**
	 * @param String $table Table to select from
	 */
	function __construct($table){
		$this->table = $table;
	}

	/**
	 * @param String $where      Column to check
	 * @param String $comparison Comparison Operator
	 * @param String $equals     Data to compare against
	 *
	 * @return Where Where object
	 */
	function where($where, $comparison, $equals){
		$this->where = new Where($this, $where, $comparison, $equals);
		return $this->where;
	}

	/**
	 * @param String $by        Column to order by
	 * @param String $direction Direction to order in
	 *
	 * @return $this
	 */
	function order($by, $direction){
		$this->by = $by;
		$this->direction = $direction;
		return $this;
	}

	/**
	 * @param int $offset Offset of records
	 *
	 * @return $this
	 */
	function offset($offset){
		$this->offset = $offset;
		return $this;
	}

	/**
	 * @param int $count Count how many records to select
	 *
	 * @return $this
	 */
	function count($count){
		$this->count = $count;
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

	function buildQuery(){
		$bindData = array();
		$query = "DELETE ";

		if($this->ignore){
			$query .= "IGNORE ";
		}

		$query .= "FROM `".$this->table."`";

		if($this->where != null){
			if($this->where instanceof Where){
				$query .= $this->where->toSQL();
				$bindData = array_merge_recursive($bindData, $this->where->getData());
			}
		}

		if(!is_null($this->by)){
			$query .= " ORDER BY `".$this->by."` ".$this->direction;
		}

		if($this->count > 0){
			$query .= " LIMIT ".$this->offset.", ".$this->count;
		}elseif($this->offset > 0){
			$query .= " LIMIT ".$this->offset.", 18446744073709551615";
		}

		return array($query, $bindData);
	}

}