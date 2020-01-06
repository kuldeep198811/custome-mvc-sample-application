<?php
namespace core;

use \config\config as Config;

class Controller extends Config
{
	public function __construct()
	{
		parent::__construct();
	}

	/* call view class */
	public function __view(string $_viewName, array $_data=[], bool $_includeHeader=true, bool $_includeLeftNav=false, bool $_includeFooter=true)
	{
		$_view = new view($_viewName, $_data, $this->_singleViewCSSFiles, $this->_singleViewJSFiles, $_includeHeader, $_includeLeftNav, $_includeFooter, $this->_seoMeta);
		echo $_view;
	}

	/* include model class */
	public function __model(string $_modelName)
	{
		$_modelName	=	str_replace("/", "", "\models\/".$_modelName);
		return new $_modelName();
	}


	/* redirect spcific file */
	public function __redirect(string $_path):void
	{
		$_fullPath = ($_path == 'index.php')? $this->_basePath:$this->_basePath.$_path;
		header("Location: $_fullPath");
		exit;
	}
}
