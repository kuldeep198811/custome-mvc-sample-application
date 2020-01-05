/***
Simple captcha libaray 
@dev_name : Manoj Joshi
@description: 
__createImage() function Create captcha image by use of GD libaray.
For Display message use rand() function here.
use function of GD libaray
Create the size of image or blank image by imagecreatetruecolor( $width, $height )
Create background color by use imagecolorallocate(resource $image , int $red , int $green , int $blue).
Draws the string at given position by imagestring(resource $image, $font, $x, $y, $string, $color )
For Draw Line use imageline ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
For create jpg image use imagejpeg ( resource $image [, string $filename [, int $quality ]] )

Parameter 
 1-  $_randomAlpha              it's store random number which assign in  __construct() function
 2-  _canvasWidth               Assign width for resource image
 3-  _canvasHeight              Assign height for resource image
 4-  _canvasBackgroundColor     Assign background color value in array format [red,green,blue] for resource image
 5-	 _canvasTextColor           Assign text color value in array format [red,green,blue] 
 6-	 _canvasLineColor           Assign text color value in array format [red,green,blue] 
 7-	 _fontSize                  Set Text Font Size
 8-  _salt                      Set Salt For Generate Hash  
 
***/