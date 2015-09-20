<?php

namespace SQL;

require("pdohelper.php");
require("database.php");
require("sqlquery.php");
require("delete.php");
require("insert.php");
require("select.php");
require("update.php");
require("where.php");

class SQL {

	/**
	 * Create a new Select query
	 *
	 * @param $table String Table to select from
	 *
	 * @return Select
	 */
	public static function select($table){
		return new Select($table);
	}

	/**
	 * Create a new Update query
	 *
	 * @param $table String Table to update
	 *
	 * @return Update
	 */
	public static function update($table){
		return new Update($table);
	}

	/**
	 * Create a new Insert query
	 *
	 * @param $table String Table to insert into
	 *
	 * @return Insert
	 */
	public static function insert($table){
		return new Insert($table);
	}

	/**
	 * Create a new Delete query
	 *
	 * @param $table String Table to delete from
	 *
	 * @return Delete
	 */
	public static function delete($table){
		return new Delete($table);
	}

	/**
	 * Create a connection to a database
	 *
	 * @param $host String Host of the database
	 * @param $user String Username of database user
	 * @param $pass String Password of database user
	 * @param $name String Name of database
	 */
	public static function connect($host, $user, $pass, $name){
		Database::setDatabaseDetails($host, $user, $pass, $name);
	}

}

