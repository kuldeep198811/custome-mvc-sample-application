<?php
namespace core;

class BaseController extends \core\controller
{
	//set class variables
	protected $_singleViewCSSFiles	=	[];
	protected $_singleViewJSFiles	=	[];
    public $_validation;
    public $_helper;
    public $_headerCartData;
    public $_seoTagsData;
	public $_colorCode = [];
    
	public function __construct($_disabled=null)
	{
		parent::__construct();
		$this->_helper = $this->__loadHelper('helper');
	}
	
	public function __checkStringIsHTML(string $_string):bool
	{
		return $_string != strip_tags($_string) ? true:false;		
	}
	
	public function __googleReCaptcha(string $_recaptcha):int
	{
        // Verify the reCAPTCHA response 
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->_recaptchaSecretKey.'&response='.$_recaptcha); 
		
        // Decode json data 
        $responseData = json_decode($verifyResponse); 
        if ($responseData->success) {
            return 1;
        } else{
            return 0;
        }
    }
	
	//Call Email Template file 
	private function __loadEmailTemplate(string $_templateName):string
	{
		$path = realpath(__DIR__.'/..');
		$path= $path.'/views/_email/'.$_templateName.'.php';
		if (file_exists($path)) {
			return	$path;exit;
		} else {
			return	'no';exit;
		}
		 
	}
	// Replace email content with specific key
	public function __replaceEmailTemplateContent(string $_templateName, array $_replaceArrayData)
	{ 
		$_emailFile = $this->__loadEmailTemplate($_templateName);
		if( $_emailFile != 'no' ){
			$_readTemplateFile = file_get_contents($this->__loadEmailTemplate($_templateName));
			if(sizeof($_replaceArrayData) > 0){
				foreach($_replaceArrayData as $key=>$value){
					$_readTemplateFile = str_replace('{{'.$key.'}}', $value, $_readTemplateFile);
				}
			}
			return $_readTemplateFile;exit;
		}else{
			return 0;
		}

	}
	
	/*
	*@description : to xss the text
	*/
	public function __xssClean(string $_string):string
	{
		// Remove any attribute starting with "on" or xmlns
		$_string = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $_string);

		// Remove javascript: and vbscript: protocols
		$_string = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $_string);
		$_string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $_string);
		$_string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $_string);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$_string = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $_string);
		$_string = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $_string);
		$_string = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $_string);

		// Remove namespaced elements (we do not need them)
		$_string = preg_replace('#</*\w+:\w[^>]*+>#i', '', $_string);

		do{
		    // Remove really unwanted tags
		    $old_data = $_string;
		    $_string = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $_string);
		}while ($old_data !== $_string);

		// we are done...
		$_string = filter_var($_string, FILTER_SANITIZE_STRING);
		return html_entity_decode( $_string );
	}
	
	// encrypts the string
	public function __encryptString(string $string):string
	{	
		//if there is no salt, generate one
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->product_salt, $string, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}

	// decrypt the string
	public function __decryptString(string $string):string
	{	
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->product_salt, base64_decode($string), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}	
	
	//Address validation START*************************
	public function isAddressValidationApi(string $country, array $post):array
	{
		if(!empty($country) && !empty($post)){
			$zipcode = $post['zipcode'];
			if(strlen($zipcode) == 4){
				$zipcode = substr_replace($zipcode, '0', 0, 0);
			}			
			$curl = curl_init();
			curl_setopt_array($curl, [
		    	CURLOPT_RETURNTRANSFER => 1,
		    	CURLOPT_URL => 'http://api.zippopotam.us/'.$country.'/'.$zipcode
			]);
			$resp = curl_exec($curl);
			$result = json_decode($resp,true);
			if(!empty($result) && !empty($result['country'])){
				
				//match response state with post state
				if($result['places'][0]['state abbreviation'] == $post['state'] ){
					return ['success'=>'success'];
				}else{
					return ['success'=>'Invalid Address'];
				}
				
			}else{
				return ['success'=>'Invalid Address'];
			}			
			curl_close($curl);
		}else{
			return [];
		}
	}

    public function _ups_validate_address(array $post):bool
	{
        if(empty($post)){
            return false;
        }

        //require_once './ups/vendor/autoload.php';
		include_once(__DIR__ . "/../library/ups/vendor/autoload.php");

        $userid     = "XXXXXXXX";
        $passwd     = "XXXXXXXX";
        $accesskey  = "XXXXXXXX";

        $address = new \Ups\Entity\Address();

        $address->setAttentionName($post['comapany']);
        #$address->setBuildingName('Test');
        $address->setAddressLine1($post['address']);
        $address->setAddressLine2($post['address2']);
        #$address->setAddressLine3('Address Line 3');
        $address->setStateProvinceCode($post['state']);
        $address->setCity($post['city']);
        $address->setCountryCode('US');
        $address->setPostalCode($post['zip']);

        $xav = new \Ups\AddressValidation($accesskey, $userid, $passwd);
        $xav->activateReturnObjectOnValidate(); //This is optional
		
        try {
            $response = $xav->validate($address, $requestOption = \Ups\AddressValidation::REQUEST_OPTION_ADDRESS_VALIDATION, $maxSuggestion = 5);

            if ($response->isValid()) {
                return true;
                #$validAddress = $response->getValidatedAddress();
                //Show user validated address or update their address with the 'official' address
                //Or do something else helpful...
            }
            return false;
            /*if ($response->noCandidates()) {
                //Do something clever and helpful to let the use know the address is invalid
            }
            if ($response->isAmbiguous()) {
                $candidateAddresses = $response->getCandidateAddressList();
                foreach($candidateAddresses as $address) {
                    //Present user with list of candidate addresses so they can pick the correct one
                }
            }*/
        } catch (Exception $e) {
            #var_dump($e);
            return false;
        }
    }
	//Address validation STOP*************
}
