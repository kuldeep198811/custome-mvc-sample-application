<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $this->_seoMeta->_title; ?> | <?php echo $this->_websiteBrandName; ?></title>
	<!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<meta name="robots" content="INDEX,FOLLOW" />
	<meta name="keywords" content="<?php echo $this->_seoMeta->_keywords; ?>" />
	<meta name="description" content="<?php echo $this->_seoMeta->_description; ?>" />
	<link rel="canonical" href="http://localhost" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo $this->_seoMeta->_title.' | '.$this->_websiteBrandName; ?>" />
	<meta property="og:description" content="<?php echo $this->_seoMeta->_description; ?>" />
	<meta property="og:url" content="https://www.testdomain.com/" />
	<meta property="og:image" content="https://www.testdomain.com/assets/images/banner/banner_1banner1hero-banner2000x650zac.jpg" />
	<meta property="og:image" content="https://www.testdomain.com/assets/images/banner/banner_1banner1hero-banner2000x650.jpg" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="<?php echo $this->_seoMeta->_description; ?>" />
	<meta name="twitter:title" content="<?php echo $this->_seoMeta->_title.' | '.$this->_websiteBrandName; ?>" />
	<meta name="twitter:url" content="https://www.testdomain.com/" />
	<meta property="twitter:image" content="https://www.testdomain.com/assets/images/banner/banner_1banner1hero-banner2000x650zac.jpg" />
	<meta property="twitter:image" content="https://www.testdomain.com/assets/images/banner/banner_1banner1hero-banner2000x650.jpg" />


	<!-- Font, Jquery, Main Style Css -->
	<?php 		
		if(isset($this->_listOfCommonCSSFiles) && !empty(array_filter($this->_listOfCommonCSSFiles))){
			foreach($this->_listOfCommonCSSFiles as $css){
	?>
		<link rel="stylesheet" type="text/css" href="<?php echo $css;?>">
	<?php
			}
		}
	?>
	
	<?php 
	
		if(isset($this->_singleViewCSSFiles) && !empty(array_filter($this->_singleViewCSSFiles))){
			foreach($this->_singleViewCSSFiles as $css){
	?>
		<link rel="stylesheet" type="text/css" href="<?php echo $css;?>">
	<?php
			}
		}
	?>
		
</head>
<body>