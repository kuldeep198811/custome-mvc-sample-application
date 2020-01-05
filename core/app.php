<?php
namespace core;
use \config\config as Config;
class App extends Config
{
	/*
		default controller name
		@var $_controllerName 
	*/
	protected $_controllerName = 'home';

	/*
		default method name
		@var $_method
	*/
	protected $_method = '__index';

	/*
		url parameters
		@var $_params, @type array
	*/
	protected $_params = [];

	/*
		allowed ip address in case of backend
		@var $_listOfAllowedIps, @type array
	*/
	protected $_listOfAllowedIps = [];

	/*
		controller class objects holder
		@var $_controller, @type object
	*/
	protected $_controller;

	/* 
		every request and reponse will go through this contructor, all major preventions and securities are 
		handling under this mehod
		@var mix , @type mix [array, objects, strings, etc]
	*/
	public function __construct()
	{
		parent::__construct();

		try{
			
			/* check if cookie settings are enabled / disabled */
			setcookie('cookie_check', '123456', time()+60*60*24*365);
			if(!isset($_COOKIE['cookie_check'])){
				exit('Browser cookies settings are not enabled. Please enable it to use this website');
			}
			
			/* checking csrf if enabled */
			if ($this->_csrfTokenEnabled == true) {

				/* check if csrf enable and csrf input matching token is not coming */
				//echo $_POST['csrf_token'];die;
				if(isset($_POST) && count(array_filter($_POST)) > 0 && (!isset($_POST['csrf_token']) || empty($_POST['csrf_token']))){
					exit('csrf token failed !');
				}else{
					if (!empty($_POST['csrf_token'])) {
						if (isset($_SESSION[$this->_sessionPrefix]['csrf_token_hash']) && hash_equals($_SESSION[$this->_sessionPrefix]['csrf_token_hash'], $_POST['csrf_token'])) {
							//echo 'passed';
							// Proceed to process the form data
						} else {
							//echo '<pre>'; print_r($_SESSION); exit;
							header('location:'.$_SERVER['HTTP_REFERER']);exit;
							exit('csrf token failed !');
							// Log this as a warning and keep an eye on these attempts
						}
					}
				}
			}
			
			/*$this->__checkAllowedClientIPs();*/
			$_url = $this->__parseUrl(); 

			/* setting up controller */
			if(isset($_url[0])) {
				$this->_controllerName = strtolower($_url[0]);
				unset($_url[0]);
			}

			/* setting up the controllers namespace and calling it */
			$this->_controller = str_replace("/", "", "\controllers\/".$this->_controllerName);

			/* it will check if class exists */
			if(class_exists($this->_controller)){

				$this->_controller = new $this->_controller;

				/* setting up controller method */
				if(isset($_url[1])) { 
					$_url[1]	=	'__'.strtolower($_url[1]);
					$_url[1] = preg_replace("/-/", "", $_url[1]);
					if(method_exists($this->_controller, $_url[1])) {
						$this->_method = $_url[1];
						unset($_url[1]);
					}
					else{
						$this->__error404();
					}
				}

				$this->_params = !empty($_url) ? array_values($_url) : [];
				if($this->_method!='__track'){
					unset($_GET);
				}
				/* check privileges if acl_list session is not setup */

				/*if(isset($_SESSION[$this->_sessionPrefix]['is_loggedin']) && $_SESSION[$this->_sessionPrefix]['is_loggedin'] == 'yes' && $this->_enableACLChecking === true && !isset($_SESSION[$this->_sessionPrefix]['acl_list'])){
					$this->__getSetUsersPrevilages();
				}*/

				if(isset($_SESSION[$this->_sessionPrefix]['acl_list']) && !empty($_SESSION[$this->_sessionPrefix]['acl_list'])){

					$_aclList	=	$_SESSION[$this->_sessionPrefix]['acl_list'];
					if($this->_controllerName != 'login' && $this->_method != 'logout' && (!isset($_aclList[$this->_controllerName]) || (isset($_aclList[$this->_controllerName]) && !isset($_aclList[$this->_controllerName][$this->_method])))){
						exit('Permission Denied! <a href="' . $this->_wwwRoot . '/dashboard">Home Page</a>');
					}

					/* if contoller is found in but allowed_  */
					/*if(array_key_exists(trim($this->_controllerName), $_aclList) && array_key_exists('*', $_aclList[$this->_controllerName])){
						exit('You are not authorized to access '. $this->_controllerName .' ! <a href="' . $this->_basePath.'/dashboard">Home Page</a>');
					}*/

				}                
				
				/*no refferer links code end */				
				call_user_func_array([$this->_controller, $this->_method], $this->_params);
			}
			else{
				$this->__error404();
			}

		}catch(\Exception $_e){


			if($this->_displayAplicationErrors == false){
				//Something to write to txt log
				$_dbLog	= 	"USER	:	".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a"). PHP_EOL .
							"URL	: 	".$_SERVER['REQUEST_URI']. PHP_EOL .
							"User	: 	".json_encode($_SESSION). PHP_EOL .
							"Error	: 	". $_e->getMessage() . PHP_EOL .
							"File  	: 	".$_e->getFile() . PHP_EOL .
							"Line  	: 	".$_e->getLine() . PHP_EOL .
							"-------------------------".PHP_EOL;
				$_logFile	=	'logs/application_logs/'.date('F_Y');

				chmod("logs/application_logs/", 0777);

				//Save string to log, use FILE_APPEND to append.
				//file_put_contents('./logs/log_'.date('F_Y').'/log_'.date("j.n.Y").'.log', $_dbLog, FILE_APPEND);
				if (!file_exists($_logFile)) {
					mkdir($_logFile, 0777, true);
				}

				file_put_contents($_logFile.'/log_'.date("j.n.Y").'.log', $_dbLog, FILE_APPEND);
				//$this->__error404();
			}else{
				//echo '<pre>'; print_r($_e); echo '</pre>';
				
				echo '	Error : '.$_e->getMessage().'<br>
						File  : '.$_e->getFile().'<br>
						Line  : '.$_e->getLine();
				
			}
			
			/* to get reponse for sap error */
			if($this->_controllerName == 'payment' && $this->_method == '__getsimulateorder'){
				echo json_encode(array('sap_error'=>$_e->getMessage()));exit;
			}
			//echo '<script>var sap_error 	=	"'.$_e->getMessage().'"</script>';
			exit;
		}
	}

	/* 
		error handler UI page
		@var, @type
	*/
	private function __error404():void{
		header('location: '. $this->_basePath.'/error/404');
		exit;
	}

	/* 
		ACL handler method
		@var, @type, @return void
	*/
	private function __getSetUsersPrevilages():void{

		if(isset($_SESSION[$this->_sessionPrefix]['user_id']) && $_SESSION[$this->_sessionPrefix]['user_id'] > 0){

			$_db 		=	new model();

			$_db->__setTable('bamko_users_acl')
					->__setArrSelectColumns(['allow_controller', 'allow_action'])
					->__setArrWhereClauseUsingAnd(['user_id' => $_SESSION[$this->_sessionPrefix]['user_id']])
					->__readRecords();

			if(!empty($_db->_arrReadData)){

					$_aclList	=	[];
					foreach($_db->_arrReadData as $eachSet){

					$_aclList[$eachSet['allow_controller']][$eachSet['allow_action']]	=	$eachSet['allow_action'];

					/* if asterisk (*) is found in controller then all controller and methods inside that will be restricted.  */
					if(trim($eachSet['allow_controller']) == '*' && $this->_method != '__logout'){
						exit('You are not authorized to access any page ! <a href="' . $this->_basePath.'/user/logout">Logout</a>');
					}

				}

				/* this is to prevent multiple hitting on database */
				$_SESSION[$this->_sessionPrefix]['acl_list']	=	$_aclList;

			}
		}

	}

	protected function __checkAllowedClientIPs():void {

		try{
			if (getenv('HTTP_CLIENT_IP'))
				$this->_ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED'))
				$this->_ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$this->_ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
				$this->_ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$this->_ipaddress = getenv('REMOTE_ADDR');
			else
				$this->_ipaddress = 'UNKNOWN';

				//echo $this->_ipaddress;
			/* localhost was throwing ip like ::1 thats why make a check to identify right IP */
				$this->_listOfAllowedIps	=	array('10.16.10.37','10.16.10.54','10.16.10.71');

			/*if(count(explode('.', $this->_ipaddress)) == 4 && !in_array($this->_ipaddress, $this->_listOfAllowedIps)){
				header("HTTP/1.1 403 Access Forbidden");
				header("Content-Type: text/plain");
				echo "You are not allowed to access this application !";
				exit;
			}*/
		}catch(\Exception $e){
			echo $e->getMessage();
			exit;
		}

	}

}
