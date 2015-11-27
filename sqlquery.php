<?php

namespace SQL;

use PDOStatement;

abstract class SQLQuery {

    /**
     * Build Query
     *
     * @return Array (Query, Data)
     */
    abstract function buildQuery();


    /**
     * Build and execute actual query
     * @return PDOStatement
     */
    function execute(){
        $query = $this->buildQuery();

        $pdo = Database::getPDO();
        $statement = $pdo->prepare($query[0]);
        $statement->execute($query[1]);

        if($statement->errorInfo()[1] > 0){
            xdebug_print_function_stack($statement->errorInfo()[2]);
            var_dump($this);
            var_dump($query);
            die();
        }

        return $statement;
    }

}