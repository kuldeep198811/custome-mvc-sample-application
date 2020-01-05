<?php
require_once __DIR__ . '../lib/Email.php';

//For SMTP
$_config['host']='smtp.gmail.com';

$_config['username']='';

$_config['password']='';

## For SMTP using own configuration

$_email = new Email();

$_subject = "This is test email";

$_message='<p>This is test email from Bamko</p><p>please dont reply on it</p>';

$_recipients = array('test@gmail.com');

$_cc=array();

$_bcc=array();

$_attachments=array();

$_fromName = 'from Name';

$_fromEmail = 'from Email';


// Send Email Through SMTP
$_send = $_email->__sendMailStmp($_fromName, $_fromEmail, $_subject, $_message, $_recipients,$cc='',$bcc='',$_attachments,$_config='');

// Send Simple Email
$_fromName = 'From Name';

$_fromEmail = 'Sender Email';

$_recipients='Recipient email';

$_message='<p>This is test email from Bamko </p><p>please dont reply on it</p>';

$_subject = "This is test email";

$_cc = 'cc email';

$_bcc = 'bcc email';


//$_send = $_email->__sendMail($_fromName, $_fromEmail, $_recipients, $_message, $_subject, $_cc='', $_bcc='');

if($_send){

	echo 'Email Sent Successfully';

}else{

	echo 'Email Not Sent';
}


?>
