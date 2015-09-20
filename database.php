<?php

namespace SQL;

class Database {

	/**
	 * @var \PDO Database Connection
	 */
	private static $pdo;

    private static $host;
    private static $user;
    private static $pass;
    private static $name;

	public static function getPDO(){
		if(Database::$pdo == null){
			Database::$pdo = new \PDO("mysql:dbname=".Database::$name.";host=".Database::$host, Database::$user, Database::$pass);
			Database::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
		}

		return Database::$pdo;
	}

	public static function setDatabaseDetails($host, $user, $pass, $name){
        Database::$host = $host;
        Database::$user = $user;
        Database::$pass = $pass;
        Database::$name = $name;
	}

}