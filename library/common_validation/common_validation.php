<?php

namespace library\common_validation;

class common_validation {

    private $_labelName;
    private $_fieldName;
    private $_value;
    private $_isInteger = false;
    public $_data = array();
    public $_errors = array();
    private $_patterns = array
        (
		
            'date_dmy' => '/^([0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4})$/u',
            'date_ymd' => '/^([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2})$/u',
            'password' => '/^([A-Za-z0-9-.;_!#@]{8,15})$/u',
			//'phone'    =>    '/^([1-9][0-9]{0,20}|[0-9]{3}-[0-9]{3}-[0-9]{4}|\+?\d+|[0-9 ()+-]+)$/u', 
			'phone'    =>    '/^([1-9][0-9]{0,20}|[0-9]{3}-[0-9]{3}-[0-9]{4}|\+?\d+|[0-9 ()+-]+)$/', 
			//'us_zip_code' => '/^([1-9][0-9]{4,4})$/u',
			'us_zip_code' => '/^([0-9][0-9]{3,4})$/',
			'canada_zip_code' => '/^[a-zA-Z0-9 ]{7,7}+$/',
			//'uk_zip_code' => '/^[a-zA-Z0-9 ]{3,8}+$/',
			'uk_zip_code' =>'/^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]?(\\s*[0-9][a-zA-Z]{1,2})?$/',
			'alpha_or_numeric'=>'/^[a-zA-Z0-9]{6,8}+$/',
			
    );

    public function __inputWithQuoto() {
        /*
        $this->_value	=	html_entity_decode($this->_value, ENT_QUOTES);
        if(!preg_match("/^([a-zA-Z'. ]+)$/",$this->_value)){
            $this->_errors[$this->_fieldName][] = "{$this->_labelName} should be Alpha Numeric.";
        }*/
        return $this;
    }
    
    // here $_type can be POST,GET,FILES
    public function __input($_fieldName, $_labelName, $_type = 'POST') {

        $this->_fieldName = $_fieldName;
        $this->_labelName = $_labelName;
        $this->_isInteger = false;

        $this->_value = ($_type == 'POST' ? (isset($_POST[$this->_fieldName]) ? ltrim($_POST[$this->_fieldName]) : '') :
                (isset($_GET[$this->_fieldName]) ? ltrim($_GET[$this->_fieldName]) : $_FILES[$this->_fieldName]));
        $this->_data[$this->_fieldName] = $this->_value;

        return $this;
    }

    public function __matchPattern($_pattern, $_customMessage = '') {

        if ($this->_value != '' && !preg_match($this->_patterns[$_pattern], $this->_value)) {
            $this->_errors[$this->_fieldName][] = (empty($_customMessage) ? "Invalid {$this->_labelName} field format." : $_customMessage);
        }

        return $this;
    }

    public function __customPattern($_regex, $_customMessage = '') {

        if ($this->_value != '' && !preg_match($_regex, $this->_value)) {
            $this->_errors[$this->_fieldName][] = (empty($_customMessage) ? "{$this->_labelName} invalid." : $_customMessage);
        }
        return $this;
    }

    public function __required($_customMessage = "") {

        if ((isset($this->_value['error']) && $this->_value['error'] == 4) /*|| (isset($this->_value['error'][0]) && $this->_value['error'][0] == 4)*/ || ($this->_value == '' || $this->_value == null)) {
            $this->_errors[$this->_fieldName][] = empty($_customMessage) ? "{$this->_labelName} is required." : $_customMessage;
        }

        return $this;
    }

    public function __min($_length) {

        if (!($this->_isInteger)) {
			
            if ((strlen($this->_value) < $_length))
                $this->_errors[$this->_fieldName][] = "{$this->_labelName} length must be greater than or equal to minimum {$_length} characters.";
        }else {
			
            if ($this->_value < $_length)
                $this->_errors[$this->_fieldName][] = "{$this->_labelName} length must be greater than or equal to minimum {$_length} characters.";
        }
        return $this;
    }

    public function __max($_length) {
        if (!($this->_isInteger)) {
            if ((strlen($this->_value) >$_length))
                $this->_errors[$this->_fieldName][] = "{$this->_labelName} length should not be more than {$_length} characters";
        }
        else {
            if ($this->_value >$_length)
                $this->_errors[$this->_fieldName][] = "{$this->_labelName} length should not be more than {$_length} characters";
        }
        return $this;
    }

    public function __confirmPassword($_fieldName, $_labelName) {

        if ($this->_value != $this->_data[$_fieldName]) {
            $this->_errors[$this->_fieldName][] = "{$_labelName} does not match with {$this->_labelName}";
        }
        return $this;
    }
	
	//strong password check
    public function __passwordCheck() 
	{
      $_password_string = trim($this->_value);
      //if(strlen($_password_string)<8 || !(preg_match('/\d/', $_password_string)) || !(preg_match('/[^a-zA-Z\d]/', $_password_string)) || !( preg_match('/[^a-zA-Z\d]/', $_password_string)) || !(preg_match('/[a-z]/', $_password_string))) 
        if(!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[",;:~`?}{>!@#\/\|\'\$%\^\&*\)\\]\(\[\+=._-])/', $_password_string))
	  {
        $this->_errors[$this->_fieldName][] =  "{$this->_labelName} should have 8 characters with a lower case, upper case, special character and a number";
     
	 }
    }

    public function __xss() {

        $this->_value = filter_var($this->_value, FILTER_SANITIZE_STRING);

        $this->_data[$this->_fieldName] = $this->_value;

        return $this;
    }

    public function __isSuccess() {
        if (empty($this->_errors)) {
            return true;
        } else {
            return false;
        }
    }

    public function __isInt() {
        if (!filter_var($this->_value, FILTER_VALIDATE_INT)) {

            $this->_errors[$this->_fieldName][] = "{$this->_labelName} should be numeric.";
        } else {

            $this->_isInteger = true;
        }
        return $this;
    }

    public function __isFloat() {
        if (!filter_var($this->_value, FILTER_VALIDATE_FLOAT)) {
            $this->_errors[$this->_fieldName][] = "{$this->_labelName} should be Float.";
        }
        return $this;
    }

    public function __isAlpha() {
        if (!filter_var($this->_value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z ]+$/")))) {
            $this->_errors[$this->_fieldName][] = "{$this->_labelName} should be alphabetic.";
        }
        return $this;
    }

    public function __isAlphaNum() {
        if (!filter_var($this->_value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^(?=.*\d)(?=.*[a-zA-Z]).{0,}$/")))) {
            $this->_errors[$this->_fieldName][] = "{$this->_labelName} should be Alpha Numeric.";
        }
        return $this;
    }

    public function __isUrl() {
        if (!filter_var($this->_value, FILTER_VALIDATE_URL)) {
            $this->_errors[$this->_fieldName][] = "{$this->_labelName} should be Url";
        }
        return $this;
    }

    public function __isEmail() {
        
        /* if(strpos($this->_value, "&#39;")!==false){
            $this->_value = str_replace("&#39;","",$this->_value);
        } */
		$this->_value	=	html_entity_decode($this->_value, ENT_QUOTES);
		
        if (!filter_var($this->_value, FILTER_VALIDATE_EMAIL)) {
            $this->_errors[$this->_fieldName][] = 'Invalid ' . strtolower($this->_labelName) . ' format.';
        }
        return $this;
    }
    public function __extractNumber()
	{
		preg_match_all('!\d+!', $this->_value, $matches);
		$this->_value=(empty($matches)?$this->_value:((isset($matches[0]) && !empty($matches[0]))?implode('',$matches[0]):$this->_value));
		return $this;
	}
    public function __passwordHash($_password) {
        /**
         * This code will benchmark your server to determine how high of a cost you can
         * afford. You want to set the highest cost that you can without slowing down
         * you server too much. 8-10 is a good baseline, and more is good if your servers
         * are fast enough. The code below aims for ≤ 50 milliseconds stretching time,
         * which is a good baseline for systems handling interactive logins.
         */
        $_timeTarget = 0.05; // 50 milliseconds
        $_cost = 8;
        $_salt = '$P27r06o9!nasda57b2M22';

        do {
            $_cost++;
            $_start = microtime(true);
            $_getHash = password_hash($_password, PASSWORD_BCRYPT, [$_cost, $_salt]);
            $_end = microtime(true);
        } while (($_end - $_start) < $_timeTarget);
        return $_getHash;
    }

    public function __matchPassword($_password, $_passwordHash) {
        return password_verify($_password, $_passwordHash) ? true : false;
    }
}
?>
