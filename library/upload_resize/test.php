<?php
require_once __DIR__ . '../src/FileUpload.php';

try{

	if(isset($_FILES['fileToUpload'])){

	  	$dir=__DIR__ .'/storage/';

	    $file_allowed_mime=['image/jpeg', 'image/pjpeg', 'image/x-citrix-jpeg','image/jpeg', 'image/pjpeg', 'image/x-citrix-jpeg','image/jpeg', 'image/pjpeg', 'image/x-citrix-jpeg','image/png', 'image/x-citrix-png', 'image/x-png','application/excel', 'application/msexcel', 'application/vnd.ms-excel','application/pdf','application/vnd.ms-office','text/plain','text/csv','text/tsv'];

	    $allowed_ext = ["gif", "jpeg", "jpg", "png","xls","pdf","txt","csv"];
	    $fileupload = new FileUpload();
      $Maxfilesize=5;  // MB

	    $_res=$fileupload->__upload($dir,$allowed_ext,$file_allowed_mime,$Maxfilesize,$_FILES['fileToUpload']);


			if(isset($_res['msg'])){

				echo 'Success! File uploaded successfully.';

			}else{

				echo 'Error!'.$_res;

			}

			/*
			* @descriptiom:code for resizing image
			* @dev_name : Parmod Kumar Pal
			*/

			if(!empty($_res['img_path'])){

				$_filename =$_res['img_path']; /* get the image path */

				$fileupload->__intImageLib($_filename); /* intialize image resize library */

				//$fileupload->__scale(50); /*To scale an image, in this case to half it's size (scaling is percentage based): */

				$fileupload->__crop(50,50); /*To crop an image Positions :CROPTOP,CROPBOTTOM,CROPCENTER,*/

				//$fileupload ->__resizeToWidth(300);  /* To resize an image according to one dimension (keeping aspect ratio):*/

				//$fileupload ->__resizeToHeight(300);  /* To resize an image according to one dimension (keeping aspect ratio):*/

				//$fileupload -> __resizeToBestFit(500, 300);  /*To resize an image to best fit a given set of dimensions (keeping aspet ratio)*/

				//$fileupload -> __resize(500, 300, $allow_enlarge = True); /*All resize functions have $allow_enlarge option which is set to false by default. You can enable by passing true to any resize function*/

				//$fileupload -> __output(); /*  outputting into the browser */

				//$fileupload ->__createFromString(base64_decode('R0lGODlhAQABAIAAAAQCBP///yH5BAEAAAEALAAAAAABAAEAAAICRAEAOw==')); /*To load an image from a string*/
				//$fileupload->__getImageAsString(); /* return the result as a string */

				$_update_image_name = 'image1.jpg'; /* change image name as per your choice */

				$save=$fileupload->__save($_update_image_name); /* save resize image */

	     }

     }else{
     	echo 'Please select upload file';
     }
}
catch (customException $e) {

	  echo $e->errorMessage();
}
?>
