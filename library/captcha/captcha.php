<?php
namespace library;
class captcha
{
	private $_randomAlpha;
	private const _canvasWidth=70;
	private const _canvasHeight=30;
	private const _canvasBackgroundColor=[41,153,123];
	private const _canvasTextColor=[255,255,255];
	private const _canvasLineColor=[220,210,60];
	private const _fontSize=12;
	private const _salt='!km7mr#sr$sm@';

	function __construct()
	{
		if( !function_exists('gd_info') )
		{
           throw new Exception('Required GD library is missing');
        }
		$_getNumber=rand();

		$this->_randomAlpha= hash('gost',$_getNumber.self::_salt);

	}

	 public  function __createImage()
	 {
		session_start();

        $_captchaCode = substr($this->_randomAlpha, 8, 6);
        $_SESSION["captchaCode"] = $_captchaCode;


        $_targetLayer = imagecreatetruecolor(self::_canvasWidth,self::_canvasHeight);

		$_captchaBackground = imagecolorallocate($_targetLayer, self::_canvasBackgroundColor[0], self::_canvasBackgroundColor[1], self::_canvasBackgroundColor[2]);

        imagefill($_targetLayer,0,0,$_captchaBackground);



        $_captchaTextColor = imagecolorallocate($_targetLayer, self::_canvasTextColor[0], self::_canvasTextColor[1], self::_canvasTextColor[2]);

		$_xSpace=round(self::_canvasWidth/10);
		$_ySpace=round(self::_canvasHeight/6);



        imagestring($_targetLayer, self::_fontSize, $_xSpace, $_ySpace, $_captchaCode, $_captchaTextColor);


		$_lineColor = imagecolorallocate($_targetLayer, self::_canvasLineColor[0],self::_canvasLineColor[1], self::_canvasLineColor[2]);



		$_xHeaderSpace1=(self::_canvasWidth-self::_canvasWidth);

		$_xHeaderSpace2=self::_canvasWidth;
		$_yHeaderSpace2=$_yHeaderSpace1=round(self::_canvasHeight/10);

		imageline($_targetLayer, $_xHeaderSpace1, $_yHeaderSpace1, $_xHeaderSpace2, $_yHeaderSpace2, $_lineColor);


		$_xFooterSpace1=(self::_canvasWidth-self::_canvasWidth);

		$_xFooterSpace2=self::_canvasWidth;
		$_yFooterSpace2=$_yFooterSpace1=(self::_canvasHeight-5);

		imageline($_targetLayer, $_xFooterSpace1, $_yFooterSpace1, $_xFooterSpace2, $_yFooterSpace2, $_lineColor);

        header("Content-type: image/jpeg");
        imagejpeg($_targetLayer);


	 }
	

}


$_object =new library\captcha();
$_object->__createImage();



?>
