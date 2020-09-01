<?php

namespace Yahmi\Database;

//todo:: think twise whether this is a class or trait would be good
/**
 * This trait will be used to build INSERT, UPDATE query
 */
trait QueryBuilder
{
	
	/**
	 * Build INSERT statement
	 * @param  [type] $table_columns [description]
	 * @param  [type] $table_name [description]
	 * @return [string-sql] [build insert into statment from parameters]
	 */
	public function buildInsertQuery($table_name, $table_columns)
	{
		$table_columns = array_keys($table_columns);
		$parameters = array_map(function($column_name){ return ":".$column_name; }, $table_columns);
		$comma_separated_columns = implode(",", $table_columns);
		$comma_separated_parameters = implode(",", $parameters);

		$insert_statement = "INSERT INTO ".$table_name." (".$comma_separated_columns.") VALUES (".$comma_separated_parameters.")";

		return $insert_statement;
	}

	/**
	 * Build INSERT statement
	 * @param  [type] $table_columns [description]
	 * @param  [type] $table_name [description]
	 * @return [string-sql] [build insert into statment from parameters]
	 */
	public function buildUpdateQuery($table_name, $table_columns,$where = null)
	{
		$table_columns = array_keys($table_columns);	
		$columns_and_parameters = array_map(function($column_name){ return $column_name."=:".$column_name; }, $table_columns);
		$comma_separated_columns_and_parameters = implode(",", $columns_and_parameters);
		$update_statement = "UPDATE ".$table_name." SET ".$comma_separated_columns_and_parameters;
		if(!is_null($where))
			$update_statement = $update_statement." WHERE ".$where;
		
		return $update_statement;
	}
	
}