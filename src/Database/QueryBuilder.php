<?php

namespace Yahmi\Database;

//todo:: think twise whether this is a class or trait would be good
/**
 * This trait will be used to build INSERT, UPDATE query
 */
trait QueryBuilder
{
	/**
	 * This method will insert data in table 
	 * @param  [String] $table_name [description]
	 * @param  [Multi Dimensional Array] $parameters [description]
	 * @return [boolean]             [If record is inserted method will return true]
	 */
	public function saveToDb($table_name,$parameters)
	{
		$insert_query = $this->buildInsertQuery($table_name, $parameters);

		return $this->performDBUpdate($insert_query,$parameters);
	}

	/**
	 * This method will update data in table 
	 * @param  [String] $table_name [description]
	 * @param  [Multi Dimensional Array] $parameters [description]
	 * @param  [String] $where [Where condition for update statement]
	 * @return [boolean]             [If record is inserted method will return true]
	 */
	public function updateToDb($table_name,$parameters, $where = null)
	{
		$update_query = $this->buildUpdateQuery($table_name, $parameters);

		return $this->performDBUpdate($update_query,$parameters);
	}
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
		$id_column = array_key_last($table_columns);
		array_pop($table_columns);
		$table_columns = array_keys($table_columns);	
		$columns_and_parameters = array_map(function($column_name){ return $column_name."=:".$column_name; }, $table_columns);
		$where_condition = " WHERE ".$id_column."=:".$id_column; 
		$comma_separated_columns_and_parameters = implode(",", $columns_and_parameters);
		$update_statement = "UPDATE ".$table_name." SET ".$comma_separated_columns_and_parameters;
		$update_statement = $update_statement.$where_condition;
		if(!is_null($where))
			$update_statement = $update_statement." AND ".$where;
		
		return $update_statement;
	}
	
}