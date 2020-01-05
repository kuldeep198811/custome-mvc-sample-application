<?php
namespace core;
use core\Controller as CoreController;
use \config\config as Config;
class Router extends Config
{

    protected $_routes = [];

    protected $_params = [];

    public function __construct(){
		parent::__construct();
	}

    public function add($_route, $_params = [])
    {

		/* its a very simple routing which we can improve in future */
		$_url	=	$this->__parseUrl();
		if($_url != ""){
			$_preservedParams	=	array_splice($_url, 2);

      /* redirect if routing available to routed URL */
			if(strtolower(implode('/', $_params)) ==	strtolower(implode('/', $_url))){
				$_coreController	=	new coreController;
				$_coreController->__redirect('/'.$_route.'/'.implode('/', $_preservedParams));
			}

			/* below code tells the program about actual controller and method */
			if(strtolower($_route)	==	strtolower(implode('/',array_slice($_url, 0, 2)))){
				$_SERVER['PATH_INFO']	=	implode('/', array_merge($_params, $_preservedParams));
			}
		}

    }


}
