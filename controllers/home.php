<?php
namespace controllers;

class home extends \core\baseController
{
	private $_registrationModel;
	
	public function __construct()
	{
		parent::__construct();
		$this->_registrationModel = $this->__model('registration');
	}
	
	public function __index()
	{		
		$this->_singleViewJSFiles 	=	[
											$this->_assetRoot . 'js/registration-form.js'.$this->_cacheVersion
										];
										
		$this->_singleViewCSSFiles	=	[										  
										
										];
									  
									  
		/* set meta data for index home page */		
		$this->_seoMeta->_title 		=	'Registration Form';
		$this->_seoMeta->_keywords		=	'Registration Keywords';
		$this->_seoMeta->_description	=	'Registration Description';

		/* registratin on submit */
		if(isset($_POST)){
			$this->__registration();
		}		
		
		if(isset($_COOKIE['unique_cookie_id'])){
			$this->_registrationModel->__getTempFormData();
		}
		
		$this->__view	('home/index',	['_websiteBrandName'=>$this->_websiteBrandName, '_tempFormData'=>$this->_registrationModel->_arrReadData]);	
	}	
	
	public function __registration()
	{
		/* form validation */
		$_formValidation = $this->__loadLibrary('common_validation');
		$_formValidation->__input('first_name', 'First Name')->__required()->__min(3)->__max(32);
		$_formValidation->__input('last_name', 'Last Name')->__required()->__min(3)->__max(32);
		$_formValidation->__input('telephone', 'Telephone Number')->__required()->__min(8)->__max(32)->__matchPattern('phone', 'Telephone number should be in a valid format.');
		$_formValidation->__input('address', 'Address')->__required()->__min(10)->__max(200);
		$_formValidation->__input('zip_code', 'Zip-Code')->__required()->__min(4)->__max(16)->__isInt();
		$_formValidation->__input('city', 'City')->__required()->__min(2)->__max(16);
		$_formValidation->__input('account_owner', 'Account Owner')->__required()->__min(3)->__max(32);
		$_formValidation->__input('iban', 'IBAN')->__required()->__min(6)->__max(34);
		
		if(!empty($_formValidation->_errors)){
			$_SESSION[$this->_sessionPrefix]['registration_errors']    =    $_formValidation->_errors;
		}else{
			
			try{
				
				$this->_registrationModel->__beginTransactions();
				
				$this->_registrationModel->__savePersonalInfo();
				$this->_registrationModel->__saveAddressInfo();
				
				$url	=	'https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data';
				
				/* curl request start */
				$ch = curl_init( $url );
				# Setup request to send json via POST.
				$payload = json_encode( array( 'customerId'=>$this->_registrationModel->_lastInsertedUserId, 'iban'=>trim($_POST['iban']), 'owner'=>trim($_POST['account_owner'])) );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				# Return response instead of printing.
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				# Send request.
				$result = curl_exec($ch);
				
				#curl error check
				if (curl_errno($ch)) {
					$error_msg = curl_error($ch);
				}else{
					
					$result	=	json_decode($result, true);
					
					$_POST['paymentDataId']	=	$result['paymentDataId'];
					
					$this->_registrationModel->__savePaymentInfo();
					$this->_registrationModel->__removePreviousTempFormData();
				}
				
				curl_close($ch);
				/* curl request end */
				
				$this->_registrationModel->__commitTransactions();
				
				$_SESSION[$this->_sessionPrefix]['registration_success']    =    "Data has been processed successfully.<br>Your Payment Data Id: {$_POST['paymentDataId']}";
				$this->__redirect('/');				
			}catch(\Exception $_e){
				
				$this->__rollBack();
			}
		}
	}
	
	public function __saveTempForm()
	{
		$values = array();
		parse_str($_POST['formData'], $values);
		unset($values['iban']); //we can't store iban without PCI license
		$_POST['formData']	=	serialize(array_filter($values));
		
		$this->_registrationModel->__removePreviousTempFormData();
		$this->_registrationModel->__saveTempFormData();
		//echo '<pre>'; print_r($values);exit;
	}
  
 }
