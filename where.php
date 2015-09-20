<?php

namespace SQL;

class Where {

    private $parent;
    private $structure = array();
    private $data = array();

    /**
     * @param SQLQuery $parent   Parent Query object
     * @param String $where      Column to check
     * @param String $comparison Comparison Operator
     * @param String $equals     Data to compare against
     */
    function __construct($parent, $where, $comparison, $equals){
        $this->parent = $parent;
        array_push($this->structure, array("AND", $where, $comparison, true));
        array_push($this->data, $equals);
    }

    /**
     * @param String  $where      Column to check
     * @param String  $comparison Comparison Operator
     * @param String  $equals     Data to compare against
     * @param Boolean $combined   Whether should be combined with previous
     *
     * @return Where
     */
    function andWhere($where, $comparison, $equals, $combined = true){
        array_push($this->structure, array("AND", $where, $comparison, $combined));
        array_push($this->data, $equals);
        return $this;
    }

    /**
     * @param String  $where      Column to check
     * @param String  $comparison Comparison Operator
     * @param String  $equals     Data to compare against
     * @param Boolean $combined   Whether should be combined with previous
     *
     * @return Where
     */
    function orWhere($where, $comparison, $equals, $combined = true){
        array_push($this->structure, array("OR", $where, $comparison, $combined));
        array_push($this->data, $equals);
        return $this;
    }

    /**
     * @return SQLQuery Parent Query object
     */
    function b(){
        return $this->parent;
    }

    /**
     * @return array Data
     */
    function getData(){
        return $this->data;
    }

    /**
     * @return String SQL String of the structure
     */
    function toSQL(){
        if(count($this->structure) == 0){
            return "";
        }

        $total = " WHERE ";
        $current = "";
        $first = true;

        foreach($this->structure as $k => $v){
            if(!$v[3]){
                $total .= "(".$current.")";
                $current = "";
            }

            if(!$first){
                $current .= " ".$v[0]." ";
            }

            $first = false;

            $current .= "`".$v[1]."` ".$v[2]." ";
            if(!is_array($this->data[$k])){
                $current .= "?";
            }else{
                $current .= "(?".str_repeat(", ?", count($this->data[$k]) - 1).")";
            }
        }

        $total .= $current;

        return $total;
    }

}