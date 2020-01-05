<?php
namespace core;
class View extends \config\config
{
	protected $_file;

	protected $_data;

	protected $_baseController;
    public $_storeName;
	public $_storeDetail;
	public $_loadPromotion;

	public function __construct(string $_file, array $_data, array $_singleViewCSSFiles, array $_singleViewJSFiles, bool $_includeHeader, bool $_includeLeftNav, bool $_includeFooter, $_seoMeta)
	{
		
		parent::__construct();
		$this->_file = $_file;
		$this->_data = $_data;

		$this->_singleViewCSSFiles	=	$_singleViewCSSFiles;
		$this->_singleViewJSFiles	=	$_singleViewJSFiles;

		$this->_includeHeader		=	$_includeHeader;
		$this->_includeLeftNav		=	$_includeLeftNav;
		$this->_includeFooter		=	$_includeFooter;
		
		$this->_baseController		=	new \core\baseController;
		
		$this->_seoMeta				=	$_seoMeta;
		
	}

	/* return valus always should be string */
	public function __toString()
	{
		return $this->__parseView();
	}

	public function __parseView()
	{
		
		try
		{
				$_file = "views/".$this->_file.'.php';  // relative to Core directory

	        if (is_readable($_file)) {
	            ob_start();

				/* include header if required */
				if($this->_includeHeader === true){
					include_once('views/_template/header.php');
				}

				/* include left_nav if required */
				if($this->_includeLeftNav === true){
					include_once('views/_template/left_nav.php');
				}

				include $_file;

				/* include footer if required */
				if($this->_includeFooter === true){
					include_once('views/_template/footer.php');
				}

				return $_string = ob_get_clean();


	        } else {
	            throw new \Exception($_file." not found");
	        }
		}
		catch (\Error $e)
		{
				//die('hi');
			echo 'Message: ' .$e->getMessage() .' in '. $e->getFile() .' Line '. $e->getLine(),  "\n";
		  //throw new \Error($a);
		}

	}

	/* generate csrf token if enabled */
	protected function __generateCsrfToken(){

		if (empty($_SESSION[$this->_sessionPrefix]['csrf_token_hash'])) {
			$_SESSION[$this->_sessionPrefix]['csrf_token_hash'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));//bin2hex(random_bytes(32));
		}
		$this->_csrfToken = $_SESSION[$this->_sessionPrefix]['csrf_token_hash'];
	}

	/* start form tag to handle form start elements and csrf globally */
	protected function __formStart(string $_action, string $_method='post', string $_formId='', string $_formClass='', string $_autocomplete="off", string $_enctype='', array $_arrAdditionalAttr=[], bool $_csrfSingleForm = true):string{
		$_enctype	=	($_enctype != '')? 'enctype="'.$_enctype.'"':'';

			$csrfTokenInput	=	'';
		if($this->_csrfTokenEnabled === true && $_csrfSingleForm === true){
			$this->__generateCsrfToken();
			$csrfTokenInput	=	'<input type="hidden" name="csrf_token" value="'.$this->_csrfToken.'" />';
		}

		return	'<form action="'.$this->_basePath.$_action.'" method="'.$_method.'" id="'.$_formId.'" class="'.$_formClass.'" autocomplete="'.$_autocomplete.'" '.$_enctype.' '.implode($_arrAdditionalAttr, ' ').'>'.$csrfTokenInput;
	}

	protected function __formEnd():string{
		return '</form>';
	}

}
