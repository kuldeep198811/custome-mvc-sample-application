<?php
namespace core;
class Router extends \config\config
{

  protected $_routes = [];

  protected $_params = [];

  public function __construct(){
    parent::__construct();
  }
  
  public function add(string $_route, array $_params = [])
  {

        /* its a very simple routing which we can improve in future */
        $_url = $_originaURL = $this->__parseUrl();
        if($_url != ""){
    
            $spliceUpTolength = count(explode('/', $_route));
            $_preservedParams	=	array_splice($_url, $spliceUpTolength);
    
            /* redirect if routing available to routed URL */
            if(strtolower(implode('/', $_params)) ==	strtolower(implode('/', $_url))){
                $_coreController	=	new controller;
                $_coreController->__redirect('/'.$_route.'/'.implode('/', $_preservedParams));
            }
            /*if(strpos(strtolower(implode('/', $_originaURL)), strtolower(implode('/', $_params))) !== false){
			
    			$_coreController	=	new controller;
    			$_redirectURL		=	'/'.str_replace(array(strtolower(implode('/', $_params))), array($_route), implode('/', $_originaURL));
    			$_coreController->__redirect($_redirectURL);
    		}*/
    
            /* below code tells the program about actual controller and method */
            $_requestURI  = implode('/',array_slice($_url, 0, $spliceUpTolength));
            if(strtolower($_route)	==	strtolower($_requestURI)){
                $_SERVER['PATH_INFO']	=	implode('/', array_merge($_params, $_preservedParams));
            }
        }

    }
  
	public function productDetailsRouting(){
		$_url	=	$this->__parseUrl();
		if($_url != ""){
			print_r($_url);
		}
	}
  
}
