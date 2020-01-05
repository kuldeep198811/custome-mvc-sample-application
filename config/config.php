<?php

namespace config;

class Config  extends session
{

	protected $_databaseConfig		=	array();

	protected $_listOfCommonCSSFiles=	array();

	protected $_listOfCommonJSFiles	=	array();

	protected 	$_serverName, $_protocol, $_extraPath, $_wwwRoot, $_assetRoot, $_sessionPrefix, $_defaultTheme, $_storeDefaultDir,
				$_storeDestination, $_bamkoManagerEmail, $_indexFile,$_assetProductImage,$_assetCategoryImage,$_assetProductImageThumb,
				$_assetProductColorThumb, $_parentDB, $_enableRecaptcha, $_recaptchaSiteKey, $_recaptchaSecretKey, $_basePath, 
				$_unixFilePath, $_unixFilePathThumb, $_assetProductImageCheck, $_assetCategoryImageCheck, $_assetProductImageThumbCheck, $_assetProductColorThumbCheck;

	protected $_csrfTokenEnabled 	= 	true; // enable/disable csrf checking

	protected $_queryDebugingMode	= 	false; // enable/disable csrf checking

	protected $_displayAllQuries	=	false; // display all running queries on page
	
	protected $_displayAplicationErrors	=	false; // display all running appliation errors on page

	protected $_enableACLChecking	=	true;

	protected $_singleViewCSSFiles  =	[];

	protected $_singleViewJSFiles	=	[];
	
	protected $_websiteBrandName='Wunder';
	
	protected $_seoMeta;
	
	protected $_enableGoogleEcommereceTool = false;

	protected $_googleEcommerceKey = 'UA-XXXXXXX-1';
	
	protected $_cacheVersion = '?version=1.0';
	
	public function __construct(){

		/* database configurations */
		$this->_databaseConfig	=	array(	'driver' 	=> 'mysql',
											'host' 		=> 'localhost',
											'database' 	=> 'wunder_app',
											'username' 	=> 'root',
											'password' 	=> '',
											'charset' 	=> 'utf8',
											'collation' => 'utf8_unicode_ci',
											'prefix' 	=> '');

		$this->_parentDB        =   'hpidirts_hpi_store_admin';


		/* application access directory configurations */
		$this->_serverName		=	$_SERVER['SERVER_NAME'];
		$this->_protocol 		=	(isset($_SERVER["HTTPS"]) ? 'https:' : 'http:');
		$this->_extraPath		=	($this->_serverName == 'localhost')? '/wunderfleet-task/':'/';
		$this->_wwwRoot 		=	$this->_protocol.'//'.$this->_serverName.$this->_extraPath;
		$this->_indexFile		=	($this->_serverName == 'localhost')? 'index.php':'';
		$this->_basePath 		=	rtrim($this->_protocol.'//'.$this->_serverName.$this->_extraPath.$this->_indexFile, '/');
		$this->_assetRoot 		=	$this->_wwwRoot .'assets/';
		$this->_primaryStaticDomain  =  'https://www.medicalappareldealstest.com'; //sitemap.xml,ror.xml,urlist.txt file static path //https://www.medicalappareldealstest.com
		
		$this->_assetProductImage 	= $this->_wwwRoot .'assets/images/product_images/';
		$this->_assetCategoryImage 	= $this->_wwwRoot .'assets/images/category_images/';
		$this->_assetProductImageThumb = $this->_wwwRoot .'assets/images/product_images/thumbnails/';
		$this->_assetProductColorThumb = $this->_wwwRoot .'assets/images/product_color_images/';
		
		$this->_assetProductImageCheck 		= 	dirname(dirname(__FILE__)) .'/assets/images/product_images/';
		$this->_assetCategoryImageCheck 	= 	dirname(dirname(__FILE__)) .'/assets/images/category_images/';
		$this->_assetProductImageThumbCheck = 	dirname(dirname(__FILE__)) .'/assets/images/product_images/thumbnails/';
		$this->_assetProductColorThumbCheck = 	dirname(dirname(__FILE__)) .'/assets/images/product_color_images/';
		
		$this->_sessionPrefix	=	'wunderapp';
		$this->_defaultTheme	=	1;
		$this->_storeDefaultDir	=	'dir/default-theme/';
		$this->_storeDestination=	'destination_folder/';
		$this->__initSession($this->_sessionPrefix);
		
		$this->_enableRecaptcha 	=	true;		
		$this->_recaptchaSiteKey    = '6Lf-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-pus';
		$this->_recaptchaSecretKey  = '6Lf-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
		
		$this->_unixFilePath        =   dirname(dirname(__FILE__)).'/assets/images/product_images/';
		$this->_unixFilePathThumb   =   dirname(dirname(__FILE__)).'/assets/images/product_images/thumbnails/';
			
		/* if index.php file is not allowed in url */
		if(trim($this->_indexFile) == ""){
			if(strpos($_SERVER['REQUEST_URI'], 'index.php') !== false){
				header('location: '.str_replace(array('index.php/', 'index.php'), array('', ''), $_SERVER['REQUEST_URI']), TRUE, 301); //permanent redirection
				//header('location: '.str_replace('index.php/', '', $_SERVER['REQUEST_URI']));
				exit;
			}
		}
		
		$this->__commonAssets();
		
		/* default seo meta */
		@$this->_seoMeta->_title       =	'Title';
	
		@$this->_seoMeta->_description =	'meta description';
	
		@$this->_seoMeta->_keywords    =	'meta keywords';
	
        
	}

	public function __commonAssets(){

		/* list of common css */
		$this->_listOfCommonCSSFiles	=	[
												'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'.$this->_cacheVersion,
												$this->_assetRoot.'css/smart_wizard.css'.$this->_cacheVersion,
												$this->_assetRoot.'css/smart_wizard_theme_circles.css'.$this->_cacheVersion,
												$this->_assetRoot.'css/smart_wizard_theme_arrows.css'.$this->_cacheVersion,
												$this->_assetRoot.'css/smart_wizard_theme_dots.css'.$this->_cacheVersion,
											];
		/* list of common js */
		$this->_listOfCommonJSFiles		=	[
												'https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'.$this->_cacheVersion,
												'https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js'.$this->_cacheVersion,
												$this->_assetRoot.'js/jquery.smartWizard.min.js'.$this->_cacheVersion,
												'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js'
											];


	}

	/* this will return url params after index.php file */
	public static function __parseUrl()
	{
		if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != ""){
			return(array_values(array_filter( explode('/', $_SERVER['PATH_INFO']))));
		}

	}

	public function __loadLibrary($_libName,$_params=array()){
		$_libName	=	str_replace("/", "", "\library\/".$_libName."\/".strtolower($_libName));

		if(is_array($_params) && !empty($_params)){
			return	new $_libName($_params);
		}else{
			return	new $_libName();
		}

	}

	/* call helper */
	public function __loadHelper($_helperName){
		$_helperName	=	str_replace("/", "", "\helpers\/".strtolower($_helperName));
		return	new $_helperName();
	}
	/* call additional controllers */
	public function __loadController($_controllerName){
		$_controllerName	=	str_replace("/", "", "\controllers\/".strtolower($_controllerName));
		return	new $_controllerName();
	}

}
