<?php
/*
============ Helper functions ====== 
@developer : Mohit Singh Rawat
*/
class Data_helper 
{
	/*
	@description : function to generate data in array format
	@param data type Array
	@return array in readable format
	*/
	public static function __dump($_dataArray=array())
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
	@parama $length
	* return random string
	*/
	public static function __getRandomString($_length = 10)
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
	*@param string
	*@return array();
	*/
	public static function __splitUri($_uriStr) 
	{
		$_uri = explode('/', $_uriStr);
		return $_uri;
	}

	/*
	@description : for escaping single quotes from string
	@return escaped string
	*/	
	public static function __mssqlEscape($_str) 
	{
		if(get_magic_quotes_gpc()){
    		$_str= stripslashes($_str);
   		}
   		return str_replace("'", "''", $_str);
	}

	/*
	@description : Pagination
	@params : $_item_per_page,$_currentPage,$_total_records,$_totalPages,$_pageUrl
	@return the paginate html
	*/
	public static function __paginate($_itemPerPage, $_currentPage, $_totalRecords, $_totalPages, $_pageUrl)
	{
	    $_pagination = '';

	    if($_totalPages > 0 && $_totalPages != 1 && $_currentPage <= $_totalPages){ //verify total pages and current page number
	     	
	        $_pagination .= '<ul class="pagination">';
	        
	        $_rightLinks    = $_currentPage + 3; 
	        $_previous       = $_currentPage - 3; //previous link 
	        $_next           = $_currentPage + 1; //next link
	        $_firstLink     = true; //boolean var to decide our first link
	        
	        if($_currentPage > 1){
				$_previousLink = ($_previous==0)?1:$_previous;
	            $_pagination .= '<li class="first"><a href="'.$_pageUrl.'?page=1" title="First">«</a></li>'; //first link
	            $_pagination .= '<li><a href="'.$_pageUrl.'?page='.$_previousLink.'" title="Previous"><</a></li>'; //previous link
	                for($_i = ($_currentPage-2); $_i < $_currentPage; $_i++){ //Create left-hand side links
	                    if($_i > 0){
	                        $_pagination .= '<li><a href="'.$_pageUrl.'?page='.$_i.'">'.$_i.'</a></li>';
	                    }
	                }   
	            $_firstLink = false; //set first link to false
	        }
	        
	        if($_firstLink){ //if current active page is first link
	            $_pagination .= '<li class="first active">'.$_currentPage.'</li>';
	        }elseif($_currentPage == $_totalPages){ //if it's the last active link
	            $_pagination .= '<li class="last active">'.$_currentPage.'</li>';
	        }else{ //regular current link
	            $_pagination .= '<li class="active">'.$_currentPage.'</li>';
	        }
	                
	        for($_i = $_currentPage+1; $_i < $_right_links ; $_i++){ //create right-hand side links
	            if($_i<=$_totalPages){
	                $_pagination .= '<li><a href="'.$_pageUrl.'?page='.$_i.'">'.$_i.'</a></li>';
	            }
	        }
	        if($_currentPage < $_totalPages){ 
					$_nextLink = ($_i > $_totalPages)? $_totalPages : $_i;
	                $_pagination .= '<li><a href="'.$_pageUrl.'?page='.$_nextLink.'" >></a></li>'; //next link
	                $_pagination .= '<li class="last"><a href="'.$_pageUrl.'?page='.$_totalPages.'" title="Last">»</a></li>'; //last link
	        }
	        
	        $_pagination .= '</ul>'; 
	    }
	    return $_pagination; //return pagination links
	}

	/*
	@description : Convert Price to Crores or Lakhs or Thousands
	@return : e.g. 1 Crore 5 thousand 
	*/
	public static function __convertCurrency($_number)
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
	* @param //$array is a multidimensional array
	* The array to be converted.
	* @return object|null
	* A std object representation of the converted array.
	*/
    public static function __toObject($_array)
    {
        $_result = json_decode(json_encode($_array), false);
    	return is_object($_result) ? $_result : null;
    }

    /**
    *@description : Converts a string or an object to an array.
	* @param string|object $var
	* String or object.
	* @return array|null
	* An array representation of the converted string or object.
	* Returns null on error.
	*/
    public static function __dumpStr($_var)
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
	* @param array $array
	* The concerned array.
	* @return mixed
	* The value of the first element, without key. Mixed type.
	*
	*/
    public static function __first($_array)
    {
        return $_array[array_keys($_array)[0]];
    }

    /**
    *
   	*@description : Returns the first element of an array.
    * @param array $array
    * The concerned array.
    * @return mixed
    * The value of the last element, without key. Mixed type.
    */
    public static function __last($_array)
    {
        return $_array[array_keys($_array)[sizeof($_array) - 1]];
    }

	/**
	*@description :  Gets a value in an array by dot notation for the keys.
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
    public static function __array_get($_key, $_array)
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
    public static function __array_set($_key, $_value, &$_array)
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
	* $string = 'The quick brown fox jumps over the lazy dog';
	* // bool(true)
	* @param string|array $needle
	* A string or an array of strings.
	* @param string       $haystack
	* The string to search in.
	* @return bool
	* True if $needle is found, false otherwise.
	*/
    public static function __contains($_needle, $_hayStack)
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
    public static function __afterLast($_search, $_string)
    {
        return $_search === '' ? $_string : ltrim(array_reverse(explode($_search, $_string))[0]);
    }

    /**
    *@description : Validate a given email address.
    * @param string $email
    * The email address to test.
    *
    * @return bool
    * True if given string is a valid email address, false otherwise. 
    */ 
    public static function __isEmail($_email)
    {
        return (filter_var($_email, FILTER_VALIDATE_EMAIL) !== false) ? true : false;
    }

    /**
	*@description : Get the current ip address of the user.
	* #### Example
	* echo ip();
	* @return string|null
	* The detected ip address, null if the ip was not detected.
	*/
    public static function __ip()
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
	reference :	<a href="http://msdn.microsoft.com/en-us/library/aa338201.aspx">
    */
    public static function __emailValidator($_emailTemplate){
    	
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
}
?>