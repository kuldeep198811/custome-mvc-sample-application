<?php
namespace models;

class Registration extends \core\model
{
	public $_lastInsertedUserId;
	
	public function __construct()
	{
		parent::__construct();
	}

	// check for existing user
	public function __savePersonalInfo()
	{
		$this	->__setTable('users_personal_info')
				->__setData([
							'first_name'=>	$_POST['first_name'],
							'last_name'	=>	$_POST['last_name'],
							'telephone' => 	$_POST['telephone']
							])
				->__createRecords();
		
		$this->_lastInsertedUserId = $this->_db->lastInsertId();
	}
	
	public function __saveAddressInfo()
	{
		
		$this	->__setTable('users_address')
				->__setData([
							'user_id'	=>	$this->_lastInsertedUserId,
							'address'	=>	$_POST['address'],
							'zip_code'	=>	$_POST['zip_code'],
							'city' 		=> 	$_POST['city']
							])
				->__createRecords();		
	}
	
	public function __savePaymentInfo()
	{
		$this	->__setTable('users_payment_info')
				->__setData([
							'user_id'			=>	$this->_lastInsertedUserId,
							'account_owner'		=>	$_POST['account_owner'],
							'payment_data_id'	=> 	$_POST['paymentDataId']
							])
				->__createRecords();		
	}
	
	public function __getTempFormData(){
		
		$this	->__setTable('users_temp_data')
				->__setArrSelectColumns(['form_data', 'step_number'])
				->__setArrWhereClauseUsingAnd(['cookie_id'=>$_COOKIE['unique_cookie_id']])
				->__setLimitOffset(1)
				->__readRecords();
				
	}
	
	public function __removePreviousTempFormData(){
		
		return  $this	->__setTable('users_temp_data')
						->__setArrWhereClauseUsingAnd(['cookie_id'=>$_COOKIE['unique_cookie_id']])
						->__deleteRecords();
		
	}
	
	public function __saveTempFormData(){
		
		$this	->__setTable('users_temp_data')
				->__setData([
							'form_data'		=>	$_POST['formData'],
							'step_number'	=>	$_POST['stepNumber']+1,
							'cookie_id'		=> 	$_COOKIE['unique_cookie_id']
							])
				->__createRecords();
		
	}

}
