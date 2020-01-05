<?php

/*
	@author 		: 	Kuldeep Singh
	@startDate		:	17-Jan-2019
	@description	:	its a single gate entry architecture/framework to handle all request.
						User can only access index.php file and assets like (css, js, images), and other php
						files and folder are not accessible directly from browser anyhow.
	@PHP-Version	:	7+
	@Database 		:	MS-SQL
	@Architecture	:	Custom MVC.
*/

error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('US/Eastern');

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);

$allowed_hosts = array('localhost:8080','localhost', '127.0.0.1');
if (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {

	// Uses a secure connection (HTTPS) if possible
	ini_set('session.cookie_secure', 1);

}

//Tell browser site should only be loaded over https //63072000 for 24 months
header("Strict-Transport-Security:max-age=63072000");
header('X-Frame-Options: SAMEORIGIN');

/* security headers */
header("X-XSS-Protection: 1; mode=block");
header("X-Permitted-Cross-Domain-Policies: none");
header('X-Content-Type-Options: nosniff');

header("Content-Security-Policy: none");

//header( "Set-Cookie: name=value; httpOnly" );
ini_set( 'session.cookie_httponly', 1 );

/* it will prevent if anyone try to include the page in iframe and accessing the website from CLI */
if (!isset($_SERVER['HTTP_HOST']) xor !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
	header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
	exit;
}

/* it only includes the files when it required without any composer (psr) file  */
spl_autoload_register(function($className) {
	
	$classFile = str_replace("\\", "/", $className.'.php');
	if (file_exists($classFile)) {
	    include_once(str_replace("\\", "/", $className.'.php'));
	}


});

/* this is to prevent the xss and sanitinze the data */
$_POST 	=	filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


/* add routing to understand modified URLs */
$router	=	new core\router();

/* pages routing */
$router->add('save-temp-form', ['controller' => 'home', 'action' => 'saveTempForm']);

/* initiat application */
$app	=	new core\app();


// close session to speed up the concurrent connections
// http://php.net/manual/en/function.session-write-close.php
session_write_close();
