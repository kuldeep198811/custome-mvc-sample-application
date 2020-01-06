<?php
namespace config;

class Session
{
	private static $_sessionStarted = false;
	private $_getkey;
	protected $_sessionPrfx;
	public 	$_sessionChain = [], $_lastStoredSessionData = [];

	/* To initialize the session	*/
	public function __initSession(string $_sessionPrefix)
	{		
		if(self::$_sessionStarted == false){
			session_start();
			self::$_sessionStarted = true;
		}

		$this->_sessionPrfx	=	$_sessionPrefix;
	}

	/* To set the session with prefix defined in config.php */
	public function __setSession(array $_arrSessions)
	{
		foreach($_arrSessions as $_key=>$_val){
			$_SESSION[$this->_sessionPrfx][$_key] = $_val;
		}

	}

	/* To get and remove the session */
	public function __getSession(string $_deleteKey, string $_flush = '')
	{
		$this->__readSession($_SESSION[$this->_sessionPrfx], $_deleteKey, $_flush);
		return (!empty($this->_lastStoredSessionData))?$this->_lastStoredSessionData:'';
	}

	/* to read session recursivly */
	private function __readSession(&$_array, $_deleteKey, $_flush)
	{
		if(is_array($_array))
		{
			foreach($_array as $_key=>&$_arrayElement)
			{
				if(is_array($_arrayElement)){
					if($_key == $_deleteKey){
						$this->_lastStoredSessionData	=	$_array[$_key];

						/* to remove any key from array */
						if($_flush == 'flush'){
							unset($_array[$_key]);
						}
						return true;
					}
					$this->__readSession($_arrayElement, $_deleteKey, $_flush);					
				}else{
					if($_key == $_deleteKey){
						$this->_lastStoredSessionData	=	$_array[$_key];

						/* to remove any key from array */
						if($_flush == 'flush'){
							unset($_array[$_key]);
						}
						return true;
					}
				}
			}
		}
	}

	/* To destroy the session */
	public function __destroySession()
	{
		if(self::$_sessionStarted == true){
			unset($_SESSION[$this->_sessionPrfx]);
		}

	}

}
