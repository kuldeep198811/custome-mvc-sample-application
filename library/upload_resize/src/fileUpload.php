<?php
namespace library\upload_resize\src;
/*
@dev_name : Virendra Kumar | Parmod
@description: This class has file uploading function & image resize functionality
*/
class FileUpload{

    public $_destination;
    public $_file;
    public $_filename;
    public $_typefl;
    public $_mime_type;
    public $_uploadfile;
    public $_fileSize;
    public $_allowed_file_extensions;
    public $_file_extensions_mime_types;

    /**
    * Variables for image resize
    */
    const CROPTOP = 1;
    const CROPCENTRE = 2;
    const CROPCENTER = 2;
    const CROPBOTTOM = 3;
    const CROPLEFT = 4;
    const CROPRIGHT = 5;

    public $_quality_jpg = 75;
    public $_quality_png = 0;

    public $_interlace = 0;

    public $_source_type;

    protected $_source_image;

    protected $_original_w;
    protected $_original_h;

    protected $_dest_x = 0;
    protected $_dest_y = 0;

    protected $_source_x;
    protected $_source_y;

    protected $_dest_w;
    protected $_dest_h;

    protected $_source_w;
    protected $_source_h;


    /*
    @dev_name : Virendra Kumar
    @description: Verify for php extenstion tag etc
    */
    protected function __checkTag()
    {

            if (is_file($this->_file["tmp_name"])) {
                $_file_content = file_get_contents($this->_file["tmp_name"]);
                // check php open tag
                if (stripos($_file_content, '<?php') !== false || stripos($_file_content, '<?=') !== false) {
                    unset($_file_content);
                    return false;
                }

                // check Common Gateway Interface (CGI)/perl-Practical Extraction and Reporting Language tag
                if (stripos($_file_content, '#!/') !== false && stripos($_file_content, '/perl') !== false) {
                    unset($_file_content);
                    return false;
                }
            }
            return true;

    }


    /*
    @dev_name : Virendra Kumar
    @description: Validate allowed mime types & boolean Return true on success, false on failure.
    */
    protected function __validateMimeType()
    {

        // validate allowed mime types that match uploaded file's extension.
        if (is_array($this->_allowed_mime) && !empty($this->_allowed_mime)) {

                $_Finfo = new \finfo();  //This class provides an object oriented interface into the fileinfo functions
                                        //Windows users must include the bundled php_fileinfo.dll DLL file in php.ini to enable this extension.
                $_file_mimetype = $_Finfo->file($this->_file["tmp_name"], FILEINFO_MIME_TYPE);

                if (is_array($this->_allowed_mime) && !in_array(strtolower($_file_mimetype), $this->_allowed_mime)) {
                    unset($_Finfo,$_file_mimetype);
                    return false;
                }else{
                    return true;
                }


        }
        return false;
    }


    /*
    @dev_name : Virendra Kumar
    @description: Upload file function & boolean Return 0 on success, false on error code no.
    */
    protected function __uploadFile(){
        $this ->__filename=uniqid().'_'.basename($this->_file['name']);
        $this ->_uploadfile = $this->_destination . $this ->__filename;
        if(move_uploaded_file($this->_file["tmp_name"], $this ->_uploadfile)){
            return true;
        }else{
            return $this->_error=$this->_file["error"];
        }
    }


    /*
    @dev_name : Virendra Kumar
    @description: Retrive error message.
    */
    protected function __uploadTimeErrorMessage($_code)
    {
        switch ($_code) {
            case UPLOAD_ERR_INI_SIZE:
                $_message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $_message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $_message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $_message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $_message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $_message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $_message = "File upload stopped by extension";
                break;

            default:
                $_message = "Unknown upload error";
                break;
        }
        return $_message;
    }


    /*
    @dev_name : Virendra Kumar
    @description: File Upload script.
    */
    public function __upload($_dir,$_file_ext_arr, $_allowed_mime, $_filesize=false, $_file=''){

        $this->_destination=$_dir;
        $this->_allowed_file_extensions=$_file_ext_arr;
        $this->_allowed_mime=$_allowed_mime;
        $this->_fileSize=$_filesize;
        $this->_file=$_file;

        //Validate directory
        if($this->_destination=='' || empty($this->_destination)){ return 'Destination folder required!'; }
        if (!is_dir($this->_destination)) {    return "<p style='color:red';>The directory $_dir not found!"; }

        /*Validate allowed file extention/mime array */
        if (!is_array($this->_allowed_file_extensions)) { return 'Allowed file extention array required!';}
        if (!is_array($this->_allowed_mime)) { return 'Allowed file mime array required!';}

        //Required file size parameter
        if($this->_fileSize=='' || !isset($this->_fileSize)){ return 'Required max upload value!'; }
        if(!is_numeric($this->_fileSize)){ return 'Required max upload value must be numeric!'; }
        if ($this->_fileSize<1) {    return "<p style='color:red';>The upload file size value is greater then 0!"; }
        $this->_fileSize=$this->_fileSize*1024*1024;


        /*Check empty file*/
        if($this->_file['size']<1){return ['msg'=>'Please select file'];}

        /*Check file size must not be greater then max size*/
        if($this->_file['size'] > $this->_fileSize){
            return ['msg'=>'File size greater then '.$this->_fileSize.' MB'];
        }

        /*Check file valid extention*/
        $this ->_typefl = pathinfo($this->_file['name'], PATHINFO_EXTENSION);
        if(!in_array($this->_typefl, $this->_allowed_file_extensions)){
            return ['msg'=>'Invalid extenstion'];
        }

        /*Check file mime*/
        if(!$this->__validateMimeType()){
            return ['msg'=>'The uploaded file has invalid mime type'];
        }

        /*Check invalid tag like php tag etc*/
        if(!$this->__checkTag()){
            return ['msg'=>'Error! Found php/cgi/perl embedded in the uploaded file.'];
        }


        if($this->__uploadFile()){
            $response = array('msg'=>true,'img_path'=>$this ->_uploadfile,'file_name'=>$this ->__filename);
            return $response;
        }else if(!$this->__uploadFile()){
          return $this->_error;
          $response = array('msg'=>$this->_error);
          return $response;
        }else{
            $_errorCode=$_fileupload->__uploadFile();
            return $this->__uploadTimeErrorMessage($_errorCode);
           die;
        }
    }

    /**
      * @dev_name : Parmod Kumar Pal
      * @description: Loads image source and its properties to the instanciated object
   */
    public function __intImageLib($_filename)
    {
        $_image_info = getimagesize($_filename);

        if (!$_image_info) {
            throw new \Exception('Could not read file');
        }

        list (
            $this->_original_w,
            $this->_original_h,
            $this->_source_type
        ) = $_image_info;

        switch ($this->_source_type) {
            case IMAGETYPE_GIF:
                $this->_source_image = imagecreatefromgif($_filename);
                break;

            case IMAGETYPE_JPEG:
                $this->_source_image = $this->__imageCreateJpegfromExif($_filename);

                // set new width and height for image, maybe it has changed
                $this->_original_w = ImageSX($this->_source_image);
                $this->_original_h = ImageSY($this->_source_image);

                break;

            case IMAGETYPE_PNG:
                $this->_source_image = imagecreatefrompng($_filename);
                break;

            default:
                throw new \Exception('Unsupported image type');
                break;
        }

        return $this->__resize($this->__getSourceWidth(), $this->__getSourceHeight());
    }

    /**
     * @description : Create instance from a strng
     * @dev_name :  Parmod Kumar Pal
     */
    public static function __createFromString($_image_data)
    {
        $_resize = new self('data://application/octet-stream;base64,' . base64_encode($_image_data));
        return $_resize;
      }


    /**
     * @description :Gets source width (return integer)
     * @dev_name : Parmod Kumar Pal
     */
    public function __getSourceWidth()
    {
        return $this->_original_w;
    }

    /**
     * @description: Gets source height (return integer)
     * @dev_name : Parmod Kumar Pal
     */
    public function __getSourceHeight()
    {
        return $this->_original_h;
    }

    /**
     * @description: Resizes image according to the given width and height
     * @dev_name : Parmod Kumar Pal
     */
    public function __resize($_width, $_height, $_allow_enlarge = false)
    {
        if (!$_allow_enlarge) {
            // if the user hasn't explicitly allowed enlarging,
            // but either of the dimensions are larger then the original,
            // then just use original dimensions - this logic may need rethinking

            if ($_width > $this->__getSourceWidth() || $_height > $this->__getSourceHeight()) {
                $_width  = $this->__getSourceWidth();
                $_height = $this->__getSourceHeight();
            }
        }

        $this->_source_x = 0;
        $this->_source_y = 0;

        $this->_dest_w = $_width;
        $this->_dest_h = $_height;

        $this->_source_w = $this->__getSourceWidth();
        $this->_source_h = $this->__getSourceHeight();

        return $this;
    }

    /**
     * @description: Create image from jpeg
     * @dev_name : Parmod Kumar Pal
     */

    public function __imageCreateJpegfromExif($_filename){
		$_img = imagecreatefromjpeg($_filename);
		return $_img; /* if image orientation requried then first enable the EXIF module on server then comment this line */

		$_exif = @exif_read_data($_filename);

		if (!$_exif || !isset($_exif['Orientation'])){
			return $_img;
		}

		$_orientation = $_exif['Orientation'];

		if ($_orientation === 6 || $_orientation === 5){
			$_img = imagerotate($_img, 270, null);
		} else if ($_orientation === 3 || $_orientation === 4){
			$_img = imagerotate($_img, 180, null);
		} else if ($_orientation === 8 || $_orientation === 7){
			$_img = imagerotate($_img, 90, null);
		}

		if ($_orientation === 5 || $_orientation === 4 || $_orientation === 7){
			imageflip($_img, IMG_FLIP_HORIZONTAL);
		}

		return $_img;
    }

    /**
     * @description :Saves new image
     * @dev_name : Parmod Kumar Pal
     */
    public function __save($_filename, $_image_type = null, $_quality = null, $_permissions = null)
    {
        $_image_type = $_image_type ?: $this->_source_type;

        $_dest_image = imagecreatetruecolor($this->__getDestWidth(), $this->__getDestHeight());

        imageinterlace($_dest_image, $this->_interlace);

        switch ($_image_type) {
            case IMAGETYPE_GIF:
                $_background = imagecolorallocatealpha($_dest_image, 255, 255, 255, 1);
                imagecolortransparent($_dest_image, $_background);
                imagefill($_dest_image, 0, 0 , $_background);
                imagesavealpha($_dest_image, true);
                break;

            case IMAGETYPE_JPEG:
                $_background = imagecolorallocate($_dest_image, 255, 255, 255);
                imagefilledrectangle($_dest_image, 0, 0, $this->__getDestWidth(), $this->__getDestHeight(), $_background);
                break;

            case IMAGETYPE_PNG:
                imagealphablending($_dest_image, false);
                imagesavealpha($_dest_image, true);
                break;
        }

        imagecopyresampled(
            $_dest_image,
            $this->_source_image,
            $this->_dest_x,
            $this->_dest_y,
            $this->_source_x,
            $this->_source_y,
            $this->__getDestWidth(),
            $this->__getDestHeight(),
            $this->_source_w,
            $this->_source_h
        );

        switch ($_image_type) {
            case IMAGETYPE_GIF:
                imagegif($_dest_image, $_filename);
                break;

            case IMAGETYPE_JPEG:
                if ($_quality === null) {
                    $_quality = $this->_quality_jpg;
                }

                imagejpeg($_dest_image, $_filename, $_quality);
                break;

            case IMAGETYPE_PNG:
                if ($_quality === null) {
                    $_quality = $this->_quality_png;
                }

                imagepng($_dest_image, $_filename, $_quality);
                break;
        }

        if ($_permissions) {
            chmod($_filename, $_permissions);
        }

        return $this;
    }

    /**
     * @description:Resizes image according to given scale (proportionally)
     * @dev_name : Parmod Kumar Pal
     */
    public function __scale($_scale)
    {
        $_width  = $this->__getSourceWidth() * $_scale / 100;
        $_height = $this->__getSourceHeight() * $_scale / 100;

        $this->__resize($_width, $_height, true);

        return $this;
    }

    /**
     * @description:Gets width of the destination image (return integer)
     * @dev_name: Parmod Kumar Pal
     */
    public function __getDestWidth()
    {
        return $this->_dest_w;
    }

    /**
     * @description:Gets height of the destination image (return integer)
     * @dev_name: Parmod Kumar Pal
     */
    public function __getDestHeight()
    {
        return $this->_dest_h;
    }

    /**
     * @description:Crops image according to the given width, height and crop position
     * @dev_name: Parmod Kumar Pal
     */
    public function __crop($_width, $_height, $_allow_enlarge = false, $_position = self::CROPCENTER)
    {
        if (!$_allow_enlarge) {
            // this logic is slightly different to resize(),
            // it will only reset dimensions to the original
            // if that particular dimenstion is larger

            if ($_width > $this->__getSourceWidth()) {
                $_width  = $this->__getSourceWidth();
            }

            if ($_height > $this->__getSourceHeight()) {
                $_height = $this->__getSourceHeight();
            }
        }

        $_ratio_source = $this->__getSourceWidth() / $this->__getSourceHeight();
        $_ratio_dest = $_width / $_height;

        if ($_ratio_dest < $_ratio_source) {
            $this->__resizeToHeight($_height, $_allow_enlarge);

            $_excess_width = ($this->__getDestWidth() - $_width) / $this->__getDestWidth() * $this->__getSourceWidth();

            $this->source_w = $this->__getSourceWidth() - $_excess_width;
            $this->source_x = $this->__getCropPosition($_excess_width, $_position);

            $this->dest_w = $_width;
        } else {
            $this->__resizeToWidth($_width, $_allow_enlarge);

            $_excess_height = ($this->__getDestHeight() - $_height) / $this->__getDestHeight() * $this->__getSourceHeight();

            $this->source_h = $this->__getSourceHeight() - $_excess_height;
            $this->source_y = $this->__getCropPosition($_excess_height, $_position);

            $this->dest_h = $_height;
        }

        return $this;
    }

    /**
     * @description:Resizes image according to the given height (width proportional)
     * @dev_name: Parmod Kumar Pal
     */
    public function __resizeToHeight($_height, $_allow_enlarge = false)
    {
        $_ratio = $_height / $this->__getSourceHeight();
        $_width = $this->__getSourceWidth() * $_ratio;

        $this->__resize($_width, $_height, $_allow_enlarge);

        return $this;
    }

    /**
     * @description:Resizes image according to the given width (height proportional)
     * @dev_name: Parmod Kumar Pal
     */
    public function __resizeToWidth($_width, $_allow_enlarge = false)
    {
        $_ratio  = $_width / $this->__getSourceWidth();
        $_height = $this->__getSourceHeight() * $_ratio;

        $this->__resize($_width, $_height, $_allow_enlarge);

        return $this;
    }

    /**
    * @description:Gets crop position (X or Y) according to the given position (integer)
    * @dev_name: Parmod Kumar Pal
    */
   protected function __getCropPosition($_expectedSize, $_position = self::CROPCENTER)
   {
       $_size = 0;
       switch ($_position) {
           case self::CROPBOTTOM:
           case self::CROPRIGHT:
               $_size = $_expectedSize;
               break;
           case self::CROPCENTER:
           case self::CROPCENTRE:
               $_size = $_expectedSize / 2;
               break;
       }
       return $_size;
   }

   /**
     * @descripton:Resizes image to best fit inside the given dimensions
     * @dev_name: Parmod Kumar Pal
     */
    public function __resizeToBestFit($_max_width, $_max_height, $_allow_enlarge = false)
    {
        if($this->__getSourceWidth() <= $_max_width && $this->__getSourceHeight() <= $_max_height && $_allow_enlarge === false){
            return $this;
        }

        $_ratio  = $this->__getSourceHeight() / $this->__getSourceWidth();
        $_width = $_max_width;
        $_height = $_width * $_ratio;

        if($_height > $_max_height){
            $_height = $_max_height;
            $_width = $_height / $_ratio;
        }

        return $this->__resize($_width, $_height, $_allow_enlarge);
    }

    /**
     * @description:Outputs image to browser (return integer $quality)
     * @dev_name: Parmod Kumar Pal
     */
    public function __output($_image_type = null, $_quality = null)
    {
        $_image_type = $_image_type ?: $this->_source_type;

        header('Content-Type: ' . image_type_to_mime_type($_image_type));

        $this->__save(null, $_image_type, $_quality);
    }

    /**
     * @description:Convert the image to string
     * @dev_name: Parmod Kumar Pal
     */
    public function __getImageAsString($_image_type = null, $_quality = null)
    {
        $_string_temp = tempnam(sys_get_temp_dir(), '');

        $this->__save($_string_temp, $_image_type, $_quality);

        $_string = file_get_contents($_string_temp);

        unlink($_string_temp);

        return $_string;
    }

    /**
    * @description:Convert the image to string with the current settings
    * @dev_name: Parmod Kumar Pal
    */
    public function __toString()
    {
        return $this->getImageAsString();
    }


}
