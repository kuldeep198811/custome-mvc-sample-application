<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - Product Detail</title>
	
	<!-- Roboto Font CSS -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
	<!-- OWL Slider CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" rel="stylesheet">
	<!-- Font Awesome CSS -->
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="<?php echo $this->_assetRoot; ?>css/style.css" rel="stylesheet">

  </head>
  <body style="background:#eee;">
	<table width="80%" style="margin:0 auto; font-family:Arial;">
		<tr>
			<td></td>
			<td style="text-align:center; padding:20px;"><img src="images/logo.png"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="background:#fff; padding:50px; text-align:center;">
				<h1 style="font-size: 30px;">Verify your Account</h1>
				<p style="margin-top: 32px; margin-bottom: 0; font-size: 18px;">Hey <name>,</p>
				<p style=" margin-bottom: 30px;">Thanks for signing up. Confirm your email address <a href="<token>" style="color:#d89300;"><email></a> to activate your account.</p>
				<a href="<token>" style="background: #d89300;color: #fff;font-size: 15px;padding: 10px 20px !important;text-transform: uppercase;border-radius: 0;border: 1px solid transparent;width: 20%;font-weight: 600;">Confirm</a>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align:center; padding:40px;">
				<h5>Stay in Touch<h5>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/facebook.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/twitter.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/insta.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/linked-in.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/youtube.png" style="margin:5px;"></a>
			</td>
			<td></td>
		</tr>
	</table>
  </body>
</html>