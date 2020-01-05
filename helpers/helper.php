<?php
namespace helpers;
/*
============ Helper functions ======
@developer : Mohit Singh Rawat
*/
class helper {

	private $_db = null;
	public function __construct(){
		$this->_db = new \core\model();
	}

	/*
	@description : function to generate data in array format
	@developer : Mohit Singh Rawat
	@param data type Array
	@return array in readable format
	*/
	public  function __dump($_dataArray=array())
	{
		if(is_array($_dataArray)){
			echo "<pre>",print_r($_dataArray),"</pre>";
		}
		else{
			return ['error-msg'=> 'Argument type should be array type'];
		}
	}

	/*
	@description : function to generate random string
	@developer : Mohit Singh Rawat
	@parama $length
	* return random string
	*/
	public  function __getRandomString($_length = 10)
	{
	    $_characters = '0123456789';
	    $_randomString = '';
	    for ($_i = 0; $_i < $_length; ++$_i) {
	        $_randomString .= $_characters[rand(0, strlen($_characters) - 1)];
	    }
	    return $_randomString;
	}

	/*
	@description : function to split uri array by delimeter '/'
	@developer : Mohit Singh Rawat
	*@param string
	*@return array();
	*/
	public  function __splitUri($_uriStr)
	{
		$_uri = explode('/', $_uriStr);
		return $_uri;
	}

	/*
	@description : for escaping single quotes from string
	@developer : Mohit Singh Rawat
	@return escaped string
	*/
	public  function __mssqlEscape($_str)
	{
		if(get_magic_quotes_gpc()){
    		$_str= stripslashes($_str);
   		}
   		return str_replace("'", "''", $_str);
	}

	/*
	@description : Pagination
	@developer : Mohit Singh Rawat
	@params : $_item_per_page,$_currentPage,$_total_records,$_totalPages,$_pageUrl
	@return the paginate html
	*/
	//pagination
   public function __paginate222222($_itemPerPage, $_currentPage, $_totalRecords, $_pageUrl)
   {
   		//echo $_totalRecords."--".$_currentPage; die;

		$_totalPages = ceil($_totalRecords/$_itemPerPage);
		$_pagination = '';

	    if($_totalPages > 0 && $_totalPages != 1 && $_currentPage <= $_totalPages){ //verify total pages and current page number

	        $_pagination .= '<ul class="pagination">';

	        $_rightLinks    = $_currentPage + 3;
	        $_previous       = $_currentPage - 1; //previous link
	        $_next           = $_currentPage + 1; //next link
	        $_firstLink     = true; //boolean var to decide our first link

	        if($_currentPage > 1){
				$_previousLink = ($_previous==0)?1:abs($_previous);
	            $_pagination .= '<li class="first"><a class="page-link" href="'.$_pageUrl.'&page/1" title="First">«</a></li>'; //first link
	            $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'&page/'.$_previousLink.'" title="Previous"><</a></li>'; //previous link
				if($_currentPage>3 ){ $_pagination .= '<li class="page-item disabled"> <a class="page-link" href="#"> ...</a></li>'; }
	                for($_i = ($_currentPage-2); $_i < $_currentPage; $_i++){ //Create left-hand side links
	                    if($_i > 0){
	                        $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'&page/'.$_i.'">'.$_i.'</a></li>';
	                    }
	                }
	            $_firstLink = false; //set first link to false
	        }


	        if($_firstLink){ //if current active page is first link
	            $_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
	        }elseif($_currentPage == $_totalPages){ //if it's the last active link
	            $_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
	        }else{ //regular current link
	            $_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
	        }



	        for($_i = $_currentPage+1; $_i < $_rightLinks ; $_i++){ //create right-hand side links
	            if($_i<=$_totalPages){
	                $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'&page/'.$_i.'">'.$_i.'</a></li>';
	            }
	        }
	        if($_currentPage < $_totalPages){
					//$_nextLink = ($_i > $_totalPages)? $_totalPages : $_i;
					$_nextLink = $_currentPage+1;
					if($_currentPage+3<=$_totalPages ){ $_pagination .= '<li class="page-item disabled"> <a class="page-link" href="#"> ...</a></li>'; }
	                $_pagination .= '<li><a title="Next" class="page-link" href="'.$_pageUrl.'&page/'.$_nextLink.'" >></a></li>'; //next link
	                $_pagination .= '<li class="last"><a class="page-link" href="'.$_pageUrl.'&page/'.$_totalPages.'" title="Last">»</a></li>'; //last link
	        }

	        $_pagination .= '</ul>';
	    }
	    return $_pagination; //return pagination links
	}

	/*
	@description : Convert Price to Crores or Lakhs or Thousands
	@developer : Mohit Singh Rawat
	@return : e.g. 1 Crore 5 thousand
	*/
	public  function __convertCurrency($_number)
	{

	    $_length = strlen($_number);
	    $_currency = '';

	    if($_length == 4 || $_length == 5)
	    {
	        // Thousand
	        $_number = $_number / 1000;
	        $_number = round($_number,2);
	        $_ext = "Thousand";
	        $_currency = $_number." ".$_ext;
	    }
	    elseif($_length == 6 || $_length == 7)
	    {
	        // Lakhs
	        $_number = $_number / 100000;
	        $_number = round($_number,2);
	        $_ext = "Lac";
	        $_currency = $_number." ".$_ext;

	    }
	    elseif($_length == 8 || $_length == 9)
	    {
	        // Crores
	        $_number = $_number / 10000000;
	        $_number = round($_number,2);
	        $_ext = "Cr";
	        $_currency = $_number.' '.$_ext;
	    }
	}

	/**
	*@description : Converts an array to an object.
	*@developer : Claus Bayer
	* @param //$array is a multidimensional array
	* The array to be converted.
	* @return object|null
	* A std object representation of the converted array.
	*/
    public  function __toObject($_array)
    {
        $_result = json_decode(json_encode($_array), false);
    	return is_object($_result) ? $_result : null;
    }

    /**
    *@description : Converts a string or an object to an array.
    *@developer : Claus Bayer
	* @param string|object $var
	* String or object.
	* @return array|null
	* An array representation of the converted string or object.
	* Returns null on error.
	*/
    public  function __dumpStr($_var)
    {
        if (is_string($_var)) {
            return str_split($_var);
        }
        if (is_object($_var)) {
            return json_decode(json_encode($_var), true);
        }
        return null;
    }

    /**
	*@description : Returns the first element of an array.
	*@developer : Claus Bayer
	* @param array $array
	* The concerned array.
	* @return mixed
	* The value of the first element, without key. Mixed type.
	*
	*/
    public  function __first($_array)
    {
        return $_array[array_keys($_array)[0]];
    }

    /**
    *
   	*@description : Returns the first element of an array.
	*@developer : Claus Bayer
    * @param array $array
    * The concerned array.
    * @return mixed
    * The value of the last element, without key. Mixed type.
    */
    public  function __last($_array)
    {
        return $_array[array_keys($_array)[sizeof($_array) - 1]];
    }

	/**
	*@description :  Gets a value in an array by dot notation for the keys.
	*@developer : Claus Bayer
	* #### Example
	* $array = [
	*      'foo' => 'bar',
	*      'baz' => [
	*          'qux => 'foobar'
	*      ]
	* ];
	* array_get( 'baz.qux', $array );
	*
	* // foobar
	* @param string $key
	* The key by dot notation.
	* @param array  $array
	* The array to search in.
	* @return mixed
	* The searched value, null otherwise.
    */
    public  function __array_get($_key, $_array)
    {
        if (is_string($_key) && is_array($_array)) {
            $_keys = explode('.', $_key);
            while (sizeof($_keys) >= 1) {
                $_k = array_shift($_keys);
                if (!isset($array[$_k])) {
                    return null;
                }
                if (sizeof($_keys) === 0) {
                    return $_array[$_k];
                }
                $_array = &$_array[$_k];
            }
        }
        return null;
    }

    /**
	 *@description :  Sets a value in an array using the dot notation.
	 * @developer : Claus Bayer
	 * array_set( string key, mixed value, array $array ): boolean
	 * #### Example 1
	 * ```php
	 * $array = [
	 *      'foo' => 'bar',
	 *      'baz' => [
	 *          'qux => 'foobar'
	 *      ]
	 * ];
	 *
	 * array_set( 'baz.qux', 'bazqux', $array );
	 *
	 * // (
	 * //     [foo] => bar
	 * //     [baz] => [
	 * //         [qux] => bazqux
	 * //     ]
	 * // )
	 * @param string $key
	 * The key to set using dot notation.
	 * @param mixed  $value
	 * The value to set on the specified key.
	 * @param array  $array
	 * The concerned array.
	 * @return bool
	 * True if the new value was successfully set, false otherwise.
	 */
    public  function __array_set($_key, $_value, &$_array)
    {
        if (is_string($_key) && !empty($_key)) {
            $_keys = explode('.', $_key);
            $_arrTmp = &$_array;
            while (sizeof($_keys) >= 1) {
                $_k = array_shift($_keys);
                if (!is_array($_arrTmp)) {
                    $_arrTmp = [];
                }
                if (!isset($_arrTmp[$_k])) {
                    $_arrTmp[$_k] = [];
                }
                if (sizeof($_keys) === 0) {
                    $_arrTmp[$_k] = $_value;
                    return true;
                }
                $_arrTmp = &$_arrTmp[$_k];
            }
        }
        return false;
    }

    /**
	*@description :Tests if a string contains a given element. Ignore case sensitivity.
	*@developer : Claus Bayer
	* $string = 'The quick brown fox jumps over the lazy dog';
	* // bool(true)
	* @param string|array $needle
	* A string or an array of strings.
	* @param string       $haystack
	* The string to search in.
	* @return bool
	* True if $needle is found, false otherwise.
	*/
    public  function __contains($_needle, $_hayStack)
    {
        foreach ((array)$_needle as $_ndl) {
            if (stripos($_hayStack, $_ndl) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
	*@description : Return the part of a string after the last occurrence of a given search value.
	*@developer : Claus Bayer
	* str_after_last( string $search, string $string ): string
	* #### Example
	* $path = "/var/www/html/public/img/image.jpg";
	*
	* str_after_last( '/' $path );
	*
	* // image.jpg
	* @param string $search
	* The string to search for.
	* @param string $string
	* The string to search in.
	* @return string
	* The found string after the last occurrence of the search string. Whitespaces at beginning will be removed.
	*/
    public  function __afterLast($_search, $_string)
    {
        return $_search === '' ? $_string : ltrim(array_reverse(explode($_search, $_string))[0]);
    }

    /**
    *@description : Validate a given email address.
    *@developer : Mohit Singh Rawat
    * @param string $email
    * The email address to test.
    *
    * @return bool
    * True if given string is a valid email address, false otherwise.
    */
    public  function __isEmail($_email)
    {
        return (filter_var($_email, FILTER_VALIDATE_EMAIL) !== false) ? true : false;
    }

    /**
	*@description : Get the current ip address of the user.
	*@developer : Mohit singh Rawat
	* #### Example
	* echo ip();
	* @return string|null
	* The detected ip address, null if the ip was not detected.
	*/
    public  function __ip()
    {
        if (php_sapi_name() == 'cli' && self::$__isCli) {
            $_ip = gethostbyname(gethostname());
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $_ip = self::LOCALHOST;
            }
            return $_ip;
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /*
	@description :  outlook email validator
	@developer : Mohit singh Rawat
	reference :	<a href="http://msdn.microsoft.com/en-us/library/aa338201.aspx">
    */
    public  function __emailValidator($_emailTemplate){

    	$_lowerCaseMessage = strtolower($_emailTemplate);

	    $_unsupportedHtmlElementsArr = array('applet', 'bdo', 'button', 'form', 'iframe', 'input', 'isindex', 'menu', 'noframes', 'noscript', 'object', 'optgroup', 'option', 'param', 'q', 'script', 'select');

	    $_unsupportedHtmlAttributesArr = array('accept', 'accept-charset', 'accesskey', 'archive', 'background', 'checked', 'classid', 'code', 'codecore', 'codetype', 'compact', 'data', 'declare', 'defer', 'disabled', 'enctype', 'longdesc', 'marginheight', 'marginwidth', 'media', 'method', 'multiple', 'noresize', 'object', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onfocus', 'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onreset', 'onselect', 'onsubmit', 'onunload', 'readonly', 'scrolling', 'selected', 'standby', 'tabindex', 'title', 'valuetype');

	    $_unsupportedCssPropertiesArr = array('azimuth', 'background-attachment', 'background-attachment', 'background-image', 'background-image', 'background-position', 'background-position', 'background-repeat', 'background-repeat', 'border-spacing', 'bottom', 'caption-side', 'clear', 'clear', 'clip', 'content', 'counter-increment', 'counter-reset', 'cue-before, cue-after, cue', 'cursor', 'display', 'display', 'elevation', 'empty-cells', 'float', 'float', 'font-size-adjust', 'font-stretch', 'left', 'line-break', 'list-style-image', 'list-style-image', 'list-style-position', 'list-style-position', 'marker-offset', 'max-height', 'max-width', 'min-height', 'min-width', 'orphans', 'outline', 'outline-color', 'outline-style', 'outline-width', 'overflow', 'overflow-x', 'overflow-y', 'pause-before, pause-after, pause', 'pitch', 'pitch-range', 'play-during', 'position', 'quotes', 'richness', 'right', 'speak', 'speak-header', 'speak-numeral', 'speak-punctuation', 'speech-rate', 'stress', 'table-layout', 'text-shadow', 'text-transform', 'text-transform', 'top', 'unicode-bidi', 'visibility', 'voice-family', 'volume', 'widows', 'word-spacing', 'word-spacing', 'z-index');

	    $_unsupportedHtmlElements = $_unsupportedHtmlAttributes = $_unsupportedCssProperties = array();

	    //>Unsupported HTML Elements
	    foreach ($_unsupportedHtmlElementsArr as $_unsupportedHtmlElement) {
	       	if (strpos($_lowerCaseMessage, '<' . $_unsupportedHtmlElement)) {
	            $_unsupportedHtmlElements[] = $_unsupportedHtmlElement;
	        }
    	}

    	//Unsupported HTML Attributes
    	foreach ($_unsupportedHtmlAttributesArr as $_unsupportedHtmlAttribute) {
	        if (strpos($_lowerCaseMessage, $_unsupportedHtmlAttribute . '=')) {
	            $_unsupportedHtmlAttributes[] = $_unsupportedHtmlAttribute;
	        }
    	}

    	//Unsupported CSS Properties
    	foreach ($_unsupportedCssPropertiesArr as $_unsupportedCssProperty) {
	        if (strpos($_lowerCaseMessage, $_unsupportedCssProperty . ':')) {
	            $_unsupportedCssProperties[] = $_unsupportedCssProperty;
	        }
    	}

    	if(count($_unsupportedHtmlElements) || count($_unsupportedHtmlAttributes) || count($_unsupportedCssProperties)){

	    	return [
	    			'status'=> true,
	    			'message' => 'Error Found!!',
	    			'Un_Supported_Html_Elements' => $_unsupportedHtmlElements,
	    			'Unsupported_HTML_Attributes' => $_unsupportedHtmlAttributes,
	    			'Unsupported_CSS_Properties' => $_unsupportedCssProperties
	    			];
		}
		else {
			return [
					'status' => false,
					'message'=> 'No Error'
					];
		}
    }

		/*
	@description :  Return/unset session array
	@developer : Ganesh
		*/

		public $_arrVarCharSession;
		function __recursiveRemoval(&$_array, $_delete_key, $_flush = '')
		{

			if(is_array($_array))
			{
				foreach($_array as $_key=>&$_arrayElement)
				{
					if(is_array($_arrayElement))
					{
						if($_key == $_delete_key)
						{
							$this->_arrVarCharSession = $_array[$_key];
							if($_flush == 'flush'){
								unset($_array[$_key]);
							}
							return 1;
						}
						$this->__recursiveRemoval($_arrayElement, $_delete_key, $_flush);
					}
					else
					{
						if($_key == $_delete_key)
						{
							$this->_arrVarCharSession = $_array[$_key];
							if($_flush == 'flush'){
								unset($_array[$_key]);
							}
							return 1;
						}
					}
				}
			}
		}


		/**
		*@description : Remove special charactor from string.
		*@developer : Virendra Kumar
		* @return string
		* Replace special charactor given charactor for seo purpose.
		*/
	    public  function __urlStrParam($_url)
	    {
	        if ($_url!='') {
	        	$_url = html_entity_decode(str_replace('&#039;','',$_url));
       		    $_findChar = array("+","'","/"," ","(",")","---","--"); //eg- Men's Shirt/T-Shirt <=> Mens-Shirt-T-Shirt
           		$_replaceChar = array("-","","-","-","","","-","-");
             	$_url = strtolower(str_replace($_findChar,$_replaceChar,$_url));
           		return $_url;
	        }
	        return false;
	    }
		
		public function __urlStrParam1($text)
		{
		  // replace non letter or digits by -
		  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

		  // transliterate
		  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		  // remove unwanted characters
		  $text = preg_replace("~[^-\w]+~", '', $text);

		  // trim
		  $text = trim($text, '-');

		  // remove duplicate -
		  $text = preg_replace('~-+~', '-', $text);

		  // lowercase
		  $text = strtolower($text);

		  if (empty($text)) {
		    return 'n-a';
		  }
		  
		  if(strpos($text, '-039')){
            $text = str_replace('-039-s', 's', $text); 
           }
           else {

           		$_findChar = array("+","'","/"," ","(",")","---","--"); //eg- Men's Shirt/T-Shirt <=> Mens-Shirt-T-Shirt
           		$_replaceChar = array("-","","-","-","","","-","-");
                return strtolower(str_replace($_findChar,$_replaceChar,$text));
           }
		  return $text;
		}

	    /**
		*@description : Create sorting url .
		*@developer : Virendra Kumar
		*/
	    public function __sortingUrl($_page,$_sortStr){
            /*Set auto select filter value in left panelof product listing*/
		    // eg. url- sort:def|sortby:lh|color:Black^White^Blue^Red
		        $_sortBy='';
			    $_urlValueArr=[];
				if($_sortStr!=null){
				  $_u1=explode('|',$_sortStr);
				  if(count($_u1)>0){
				  	$_sortBy=($_u1[0]=='sortby:hl')?'desc':'asc';
					foreach ($_u1 as $_value) {

					  $_option=explode(':', $_value);

					  if($_option[1]!=''){
						$_attributes=explode('^', $_option[1]);
							if(count($_attributes)>0){
							foreach ($_attributes as $_value) {
							   $_urlValueArr[$_option[0]][]=$_value;
							}

							}
					  }
					}

				  }
				}


				$_url="";
				$_col="|";

			   if(array_key_exists('sortby', $_urlValueArr)){
				 $_url.='/sortby:'.$_urlValueArr['sortby'][0];
			   }
				$_colorIn="";
				if(array_key_exists('color',$_urlValueArr)){
				   $_url.=$_col.'color:';
				   $_c="'";
				   $cap="";
				   $_coma=""; //add cap if more then attributes
				   foreach ($_urlValueArr['color'] as $_key => $_value) {
					 $_url.=$cap.$_value;

					 $_colorIn.=$_coma.$_c.$_value.$_c;
					 $cap="^";
					 $_coma=",";
				   }
				}

				$_sizeIn="";
				if(array_key_exists('size',$_urlValueArr)){
				   $_url.=$_col.'size:';
				   $_c="'";
				   $cap="";
				   $_coma=""; //add cap if more then attributes
				   foreach ($_urlValueArr['size'] as $_key => $_value) {
					 $_url.=$cap.$_value;

					 $_sizeIn.=$_coma.$_c.$_value.$_c;
					 $cap="^";
					 $_coma=",";
				   }
				}
				/*Add 9/5/2019 at 11.47 am*/
				$_typeIn="";
				if(array_key_exists('type',$_urlValueArr)){
				   $_url.=$_col.'type:';
				   $_c="'";
				   $cap="";
				   $_coma=""; //add cap if more then attributes
				   foreach ($_urlValueArr['type'] as $_key => $_value) {
					 $_url.=$cap.$_value;

					 $_typeIn.=$_coma.$_c.$this->__typeUrlStr($_value,1).$_c;
					 $cap="^";
					 $_coma=",";
				   }
				}

				$_genderIn="";
				if(array_key_exists('gender',$_urlValueArr)){
				   $_url.=$_col.'gender:';
				   $_c="'";
				   $cap="";
				   $_coma=""; //add cap if more then attributes
				   foreach ($_urlValueArr['gender'] as $_key => $_value) {
					 $_url.=$cap.$_value;

					 $_genderIn.=$_coma.$_c.$_value.$_c;
					 $cap="^";
					 $_coma=",";
				   }
				}

			    /*Add category filter*/
				$_cateIdIN="";
				if(array_key_exists('cate',$_urlValueArr)){
				   $_url.=$_col.'cate:';
				   $_c="";
				   $cap="";
				   $_coma=""; //add cap if more then attributes
				   foreach ($_urlValueArr['cate'] as $_key => $_value) {
				     $_url.=$cap.$_value;

				     $_cateIdIN.=$_coma.$_c.$_value.$_c;
				     $cap="^";
				     $_coma=",";
				   }
				}

			    /*Price*/
			    $_price=[];
			    if(array_key_exists('price',$_urlValueArr)){
			       $_url.=$_col.'price:';
			       $_c="'";
			       $cap="";
			       $_coma=""; //add cap if more then attributes
			       foreach ($_urlValueArr['price'] as $_key => $_value) {
			         $_url.=$cap.$_value;

			         $_price[]=$_value;
			         $cap="^";
			         $_coma=",";
			       }
			    }

			    $_brandIn="";
				if(array_key_exists('brand',$_urlValueArr)){
				   $_url.=$_col.'brand:';
				   $_c="'";
				   $cap="";
				   $_coma=""; //add cap if more then attributes
				   foreach ($_urlValueArr['brand'] as $_key => $_value) {
					 $_url.=$cap.$_value;

					 $_brandIn.=$_coma.$_c.$_value.$_c;
					 $cap="^";
					 $_coma=",";
				   }
				}

				//get the page numbert from query string
				$_pageExplode=explode("=",$_page);
				if(count($_pageExplode)>1){
				  $_page = $_pageExplode[1];
				}
				// end
				$_productLimit = 9;
				if($_page == 1){
				  $_pageOffset = 0;
				}
				elseif($_page>1){
				  $_pageOffset = $_page*$_productLimit - $_productLimit;
				}
			   // $_prd_by_cateid=$this->_products->__getProductsByCateID($_cateID);


				return ['_urlValueArr'=>$_urlValueArr,
				'_colorIn'=>$_colorIn,
				'_sizeIn'=>$_sizeIn,
				'_pageOffset'=>$_pageOffset,
				'_sortby'=>$_sortBy,
				'_productLimit'=>$_productLimit,
				'_page'=>$_page,
				'_cateIdIN'=>$_cateIdIN,
				'_price'=>$_price,
				'_brandIn'=>$_brandIn,
				'_typeIn'=>$_typeIn,
				'_genderIn'=>$_genderIn
			];

	    }

		public function __paginateV2($_itemPerPage, $_currentPage, $_totalRecords, $_pageUrl, $_sortString) {
			//echo $_totalRecords."--".$_currentPage; die;

			$_totalPages = ceil($_totalRecords/$_itemPerPage);
			$_pagination = '';
          
			if($_totalPages > 0 && $_totalPages != 1 && $_currentPage <= $_totalPages){ //verify total pages and current page number

				$_pagination .= '<ul class="pagination">';

				$_rightLinks    = $_currentPage + 3;
				$_previous       = $_currentPage - 1; //previous link
				$_next           = $_currentPage + 1; //next link
				$_firstLink     = true; //boolean var to decide our first link

				if($_currentPage > 1){
					$_previousLink = ($_previous==0)?1:abs($_previous);
					$_pagination .= '<li class="first"><a class="page-link" href="'.$_pageUrl.'/page/1/'.$_sortString.'" title="First">«</a></li>'; //first link
					$_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'/page/'.$_previousLink.'/'.$_sortString.'" title="Previous"><</a></li>'; //previous link
					if($_currentPage>3 )
					{ 
					  $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'/page/1/'.$_sortString.'">1</a></li>';
					  if($_currentPage!=4)
					  {	  
					  $_pagination .= '<li class="page-item disabled"> <a class="page-link" href="#"> ...</a></li>'; 
					  }
					}
						for($_i = ($_currentPage-2); $_i < $_currentPage; $_i++){ //Create left-hand side links
							if($_i > 0){
								$_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'/page/'.$_i.'/'.$_sortString.'">'.$_i.'</a></li>';
							}
						}
					$_firstLink = false; //set first link to false
				}


				if($_firstLink){ //if current active page is first link
					$_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
				}elseif($_currentPage == $_totalPages){ //if it's the last active link
					$_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
				}else{ //regular current link
					$_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
				}



				for($_i = $_currentPage+1; $_i < $_rightLinks ; $_i++){ //create right-hand side links
					if($_i<=$_totalPages){
						$_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'/page/'.$_i.'/'.$_sortString.'">'.$_i.'</a></li>';
					}
				}
				if($_currentPage < $_totalPages){
						//$_nextLink = ($_i > $_totalPages)? $_totalPages : $_i;
						$_nextLink = $_currentPage+1;
						if($_currentPage+3<=$_totalPages )
						{ 
					      $_remains=($_totalPages-$_currentPage);
						  //echo $_remains;die;
						  if($_remains!=3)
						  {	  
						   $_pagination .= '<li class="page-item disabled"><a class="page-link" href="#"> ...</a></li>'; 
						  }
						  $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrl.'/page/'.$_totalPages.'/'.$_sortString.'">'.$_totalPages.'</a></li>';
						}
						$_pagination .= '<li><a title="Next" class="page-link" href="'.$_pageUrl.'/page/'.$_nextLink.'/'.$_sortString.'" >></a></li>'; //next link
						$_pagination .= '<li class="last"><a class="page-link" href="'.$_pageUrl.'/page/'.$_totalPages.'/'.$_sortString.'" title="Last">»</a></li>'; //last link
				}

				$_pagination .= '</ul>';
			}
			return $_pagination; //return pagination links
		}

		/**
		*@description : Sort size order given sort order value.
		*@developer : Virendra Kumar
		* @return array
		*/
	    public  function __sizeSortForFilter($_sizeResult,$_sortOrder)
	    {
	        
	        sort($_sizeResult);
	        $_sizeResult=array_unique($_sizeResult);

    	    $_sortOrderArr=$_size=[];
			if(count($_sortOrder)>0 && count($_sizeResult)>0){
				foreach ($_sortOrder as $_value) {
					$_sortOrderArr[$_value['sku']]=$_value['sku_sort_order'];
			    }
		
			    foreach ($_sizeResult as $_key=>$_value) {
					$_sizeKey=(isset($_sortOrderArr[$_value]))?$_sortOrderArr[$_value]:0;
					$_size[$_sizeKey.'.'.$_key]=$_value;
			    }
			    ksort($_size);
			}
			return $_size;
			
	    }


	    /*
	@description : Pagination
	@developer : Mohit Singh Rawat
	@params : $_item_per_page,$_currentPage,$_total_records,$_totalPages,$_pageUrl
	@return the paginate html
	*/
	//pagination
   public function __paginate($_itemPerPage, $_currentPage, $_totalRecords, $_pageUrl,$_pageUrlWithoutQueryStr,$_keyword,$_query_string)
   {
   		//echo $_totalRecords."--".$_currentPage; die;

		$_totalPages = ceil($_totalRecords/$_itemPerPage);
		$_pagination = '';

	    if($_totalPages > 0 && $_totalPages != 1 && $_currentPage <= $_totalPages){ //verify total pages and current page number

	        $_pagination .= '<ul class="pagination">';

	        $_rightLinks    = $_currentPage + 3;
	        $_previous       = $_currentPage - 1; //previous link
	        $_next           = $_currentPage + 1; //next link
	        $_firstLink     = true; //boolean var to decide our first link

	        if($_currentPage > 1){
				$_previousLink = ($_previous==0)?1:abs($_previous);
	            $_pagination .= '<li class="first"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/1/'.$_query_string.'" title="First">«</a></li>'; //first link
	            $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/'.$_previousLink.'/'.$_query_string.'" title="Previous"><</a></li>'; //previous link
				if($_currentPage>3)
				{ 
				  $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/1/'.$_query_string.'">1</a></li>';
				  if($_currentPage!=4)
				  {	  
				   $_pagination .= '<li class="page-item disabled"> <a class="page-link" href="#"> ...</a></li>'; 
				  }
				}
	                for($_i = ($_currentPage-2); $_i < $_currentPage; $_i++){ //Create left-hand side links
	                    if($_i > 0){
	                        $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/'.$_i.'/'.$_query_string.'">'.$_i.'</a></li>';
	                    }
	                }
	            $_firstLink = false; //set first link to false
	        }


	        if($_firstLink){ //if current active page is first link
	            $_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
	        }elseif($_currentPage == $_totalPages){ //if it's the last active link
	            $_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
	        }else{ //regular current link
	            $_pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$_currentPage.'</a></li>';
	        }



	        for($_i = $_currentPage+1; $_i < $_rightLinks ; $_i++){ //create right-hand side links
	            if($_i<=$_totalPages){
	                $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/'.$_i.'/'.$_query_string.'">'.$_i.'</a></li>';
	            }
	        }
	        if($_currentPage < $_totalPages){
					//$_nextLink = ($_i > $_totalPages)? $_totalPages : $_i;
					$_nextLink = $_currentPage+1;
					if($_currentPage+3<=$_totalPages )
					{  
				       $_remains=($_totalPages-$_currentPage);
                       if($_remains!=3)
                       {						   
				        $_pagination .= '<li class="page-item disabled"> <a class="page-link" href="#"> ...</a></li>'; 
					   }
					   $_pagination .= '<li class="page-item"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/'.$_totalPages.'/'.$_query_string.'">'.$_totalPages.'</a></li>';
					}
					
	                $_pagination .= '<li><a title="Next" class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/'.$_nextLink.'/'.$_query_string.'" >></a></li>'; //next link
	                $_pagination .= '<li class="last"><a class="page-link" href="'.$_pageUrlWithoutQueryStr.'/page/'.$_totalPages.'/'.$_query_string.'" title="Last">»</a></li>'; //last link
	        }

	        $_pagination .= '</ul>';
	    }
	    return $_pagination; //return pagination links
	}

		/**
		*@description : Remove special charactor from sitemap xml string.
		*@developer : Virendra Kumar
		* @return string
		* Replace special charactor given charactor for seo purpose.
		*/
	    public  function __siteMapUrlStr($_url)
	    {
	        
	        if ($_url!='') {
	            $_url	=	html_entity_decode($_url, ENT_QUOTES);
	        	$_findChar = array("&#38;","&#039;"); //eg- Men's Shirt/T-Shirt <=> Mens-Shirt-T-Shirt
           		$_replaceChar = array("&","");
	        	$_url = str_replace($_replaceChar,$_findChar,$_url);
       			
       			$_findChar = array("+","'","/"," ","(",")","---","--"); //eg- Men's Shirt/T-Shirt <=> Mens-Shirt-T-Shirt
           		$_replaceChar = array("-","","-","-","","","-","-");
             	$_url = strtolower(str_replace($_findChar,$_replaceChar,$_url));
           		return $_url;
	        }
	        return false;
	    }
	    
	    /**
		*@description : Remove special charactor from filter type option.
		*@developer : Virendra Kumar
		* @return string
		* Replace special charactor given charactor for seo purpose.
		*/
	    public  function __typeUrlStr($_url,$_replaceType=0)
	    {
	        
	        if ($_url!='') {
	        	// $_findChar = array("&#38;","&#39;"); //eg- Men's Shirt/T-Shirt <=> Mens-Shirt-T-Shirt
          //  		$_replaceChar = array("&","'");
	        	// $_url = html_entity_decode(str_replace($_findChar,$_replaceChar,$_url));
       			
       			$_findChar = array(" ","&quot;","&#039;"); 
           		$_replaceChar = array("-","feet","inch");
           		if($_replaceType==0)
           			$_url = strtolower(str_replace($_findChar,$_replaceChar,$_url));

           		if($_replaceType==1)
           			$_url = strtolower(str_replace($_replaceChar,$_findChar,$_url));

           		return $_url;
	        }
	        return false;
	    }
	    
	    /**
	*@description : Create breadcrame.
	*@developer : Virendra Kumar
	* @return string
	* Replace special charactor given charactor for seo purpose.
	*/
	public function __breadCrumbArray($_child,$_CateArr,$_array=array() ){
      //$_array = array();
      if(array_key_exists($_child, $_CateArr)){
          $_a=$_CateArr[$_child];

          if($_a['parent_id']>0){
            $_array=$this->__breadCrumbArray($_a['parent_id'],$_CateArr,$_array);
            //$_array = array_merge($_array, $_a);
          }
         $_array[$_child] = $_a;
      }
      return $_array;

   }


   	/**
	*@description : Create breadcrame.
	*@developer : Virendra Kumar
	* @return string
	* Replace special charactor given charactor for seo purpose.
	*/
	public function __breadCrumb($_cateID,$_childCateArr,$_categoryName=null){
      	$_crumbs=$this->__breadCrumbArray($_cateID,$_childCateArr,$_array=array());
        
		$_breadCrumb = [];
        if(!empty($_crumbs))
		{

            foreach ($_crumbs as $_crumb) {
                if(!empty($_crumb['category_name']) && $_crumb['category_id']!=$_cateID){

                    if(isset($_catArr) && $_catArr['0']==strtolower($_crumb['category_name'])){
                        $_breadCrumb [] = [
                                        'category/'.$_crumb['slug_name'] => strtolower($_crumb['category_name'])
                                    ];
                    } else {
                        $_breadCrumb [] = [
                                        'category/'.$_crumb['slug_name'] => $_crumb['category_name']
                                    ];
                    }
                }
            }
             $_breadCrumb[]=($_categoryName==null)?['category/'.$_crumb['slug_name'] => $_crumb['category_name']]:[''=>$_categoryName];
        }

        if(empty($_breadCrumb))
		    {
            $_breadCrumb[] = [''=>$_categoryName];
        }

        return $_breadCrumb;

   }

}
?>
