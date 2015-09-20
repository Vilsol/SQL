<?php

namespace SQL;

class Select extends SQLQuery {

	private $where;
	private $table;
    private $distinct = false;
	private $columns = array();
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
	 * @param String $column Column to select
	 * @param String $as     Alias name of the column
	 *
	 * @return $this
	 */
	function column($column, $as = null){
		if($as === null){
			$this->columns[$column] = $column;
		}else{
			$this->columns[$column] = $as;
		}
		return $this;
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
	 * @param String $where      Column to check
	 * @param String $comparison Comparison Operator
	 * @param String $selection  Selection type (ANY, EXISTS, etc)
	 * @param String $table      Table to select from
	 *
	 * @return Select Subquery Select Object
	 */
	function whereSub($where, $comparison, $selection, $table){
		$this->where = array(new Select($table), $where, $comparison, $selection);
		return $this->where[0];
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
     * @param bool $distinct Whether to select distinct or all rows
     *
     * @return $this
     */
    function distinct($distinct){
        $this->distinct = $distinct;
        return $this;
    }

	function buildQuery(){
		$bindData = array();
		$query = "SELECT ";

        if($this->distinct){
            $query .= "DISTINCT ";
        }

		if(is_array($this->columns) && count($this->columns) > 0){
			$first = true;
			foreach($this->columns as $k => $v){
				if(!$first){
					$query .= ", ";
				}

				$first = false;

				if($k == $v){
					$query .= $k;
				}else{
					$query .= $k." as ".$v;
				}
			}
		}else{
			$query .= "*";
		}

		$query .= " FROM `".$this->table."`";

		if($this->where != null){
			if($this->where instanceof Where){
				$query .= $this->where->toSQL();
				$bindData = array_merge_recursive($bindData, $this->where->getData());
			}elseif(is_array($this->where) && $this->where[0] instanceof Select){
				$subQuery = $this->where[0]->buildQuery();
				$query .= " WHERE `".$this->where[1]."` ".$this->where[2]." ".$this->where[3]." (".$subQuery[0].")";
				$bindData = array_merge($bindData, $subQuery[1]);
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