<?php
namespace config;
class Session
{

	private static $_sessionStarted = false;
	private $_getkey;
	protected $_sessionPrfx;
	public 	$_sessionChain = [], $_lastStoredSessionData = [];

	/* To initialize the session	*/
	public function __initSession($_sessionPrefix)
	{
		if(self::$_sessionStarted == false)
		{
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

	/* To get the session with prefix defined in config.php	*/
	public function __getSession(string $_key, array $_arrSessions	=	[])
	{

		$_arrSessions	=	empty($_arrSessions)? $_SESSION[$this->_sessionPrfx]:$_arrSessions;
		foreach($_arrSessions as $_index=>$_sessionData){

			if($_index == $_key){

				$this->_lastStoredSessionData[$_key]	=	$_sessionData;
				$this->_sessionChain[]	=	$_index;
				return $_sessionData;

			}else if(is_array($_sessionData) && $this->__findKey($_key, $_sessionData) !== false){

				$this->_lastStoredSessionData[$_key]	=	$_sessionData;
				$this->_sessionChain[]	=	$_index;
				return $this->__getSession($_key, $_sessionData);

			}else{
				//nothing
			}

		}

		if($_key != $_sessionData){
				$this->_sessionChain=[];
		}
		return false;

	}

	public function __findKey($_keySearch, $_array)
	{
		if(!empty($_array) && is_array($_array)){
	    foreach ($_array as $_key => $_item) {
	        if (strtolower($_key) == strtolower($_keySearch)) {
	            return true;
	        } elseif (is_array($_item) && $this->__findKey($_item, $_keySearch)) {
	            return true;
	        }
	    }
		}
	 	//return false;
	}

	public function __flushSeesion(){

		$_chain	=	$this->_sessionChain;
		switch (count($_chain)) {
		    /*case 1:
		        unset($_SESSION[$this->_sessionPrfx][$_chain[0]]);
		        break;
		    case 2:
		        unset($_SESSION[$this->_sessionPrfx][$_chain[0]][$_chain[1]]);
		        break;
		    case 3:
		        unset($_SESSION[$this->_sessionPrfx][$_chain[0]][$_chain[1]][$_chain[2]]);
		        break;
				case 4:
		        unset($_SESSION[$this->_sessionPrfx][$_chain[0]][$_chain[1]][$_chain[2]][$_chain[3]]);
		        break;
				case 5:
		        unset($_SESSION[$this->_sessionPrfx][$_chain[0]][$_chain[1]][$_chain[2]][$_chain[3]][$_chain[4]]);
		        break;
*/
		}

	}

	/* To destroy the session */
	public function __destroySession(){

		if(self::$_sessionStarted == true)
		{
			unset($_SESSION[$this->_sessionPrfx]);
		}

	}

}
