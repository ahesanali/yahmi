<?php

namespace Yahmi\Functions;

class StringUtils
{
	/**
	 * This function converts camel case to - separated words example ProductList to product-list
	 * @param  [type] $input_string [description]
	 * @return [type]               [description]
	 */
	public static function camel2dashed($input_string) {
    	return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $input_string));
	}

	
}