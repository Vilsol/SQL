<?php

namespace SQL;

class Update extends SQLQuery {

	private $table;
	private $where = array();
	private $offset = 0;
	private $count = 0;
	private $by;
	private $direction;
	private $update = array();
	private $updateData = array();

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
	 * @param String $column Column to update
	 * @param String $data   Data to update with
	 *
	 * @return $this
	 */
	function update($column, $data){
		array_push($this->update, $column);
		array_push($this->updateData, $data);
		return $this;
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

	function buildQuery(){
		$bindData = array();
		$query = "UPDATE `".$this->table."`";

		if(is_array($this->update) && count($this->update) > 0){
			$query .= " SET ";

			$first = true;
			foreach($this->update as $k => $v){
				if(!$first){
					$query .= ", ";
				}

				$first = false;

				$query .= $v." = ?";
			}

			$bindData = array_merge($bindData, $this->updateData);
		}else{
			xdebug_print_function_stack("No columns set to update!");
			var_dump($this);
			die();
		}

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

		return array($query, PDOHelper::flatten($bindData));
	}

}