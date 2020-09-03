<?php
use Yahmi\Config\Config;
use PHPUnit\Framework\TestCase;
use Yahmi\Validation\Validator;

class ValidatorTest extends TestCase
{
	public function test_empty_data_validation()
	{
	  	$rules = [
            'first_name'  => ['required'] ,
            'last_name'   => ['required'] ,
        ];
        $input_data = [];
        //validate the request
        $validator_instance = Validator::makeValidator($input_data, $rules, true);
		$this->assertEquals($validator_instance->isValidationFailed(), true);
	}
	public function test_single_parameter_validation()
	{
	  	$rules = [
            'first_name'  => ['required'] ,
            'last_name'   => ['required'] ,
        ];
        $input_data = ['first_name'=>'ahesanali'];
        //validate the request
        $validator_instance = Validator::makeValidator($input_data, $rules, true);
		$this->assertEquals($validator_instance->isValidationFailed(), true);
	}
	public function test_single_parameter_validation_flavour2()
	{
	  	$rules = [
            'first_name'  => ['required'] ,
            'last_name'   => ['required'] ,
        ];
        $input_data = ['last_name'=>'ahesanali'];
        //validate the request
        $validator_instance = Validator::makeValidator($input_data, $rules, true);
		$this->assertEquals($validator_instance->isValidationFailed(), true);
	}
	public function test_success_validation()
	{
	  	$rules = [
            'first_name'  => ['required'] ,
            'last_name'   => ['required'] ,
        ];
        $input_data = ['first_name'=>'ahesanali','last_name'=>'suthar'];
        //validate the request
        $validator_instance = Validator::makeValidator($input_data, $rules, true);
		$this->assertEquals($validator_instance->isValidationFailed(), false);
	}
}