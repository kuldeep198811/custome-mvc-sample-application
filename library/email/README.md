## Email Lib

## Uses
### Do "require  __DIR__ . '../lib/Email.php';"; //filepath

## Just Create object of this class
$_email = new Email();

=> For SMTP Pass configuration No Mandatory Test SMTP settings set in code
$_config['host']=''; /*email host like smtp.gmail.com */

$_config['username']=''; /*email*/

$_config['password']=''; /*email password */

=> Call sendMailStmp function and Pass the params
$_subject = "This is test email";

$_message='<p>This is test email from Bamko</p><p>please dont reply on it</p>';

$_recipients = array('test@gmail.com'); <!--- can send emails to multiple users --- !>

$_cc=array('cc@email.com'); <!--- can send emails to multiple users --- !>

$_bcc=array('bcc@email.com'); <!--- can send emails to multiple users --- !>

$_attachments=array();

$_fromName = 'from Name';

$_fromEmail = 'from Email';


$_email->__sendMailStmp($_fromName $_fromEmail, $_subject, $_message, $_recipients,$_cc,$_bcc,$_attachments,$_config);

***********************************************************************

##or Simple Email Call SendMail function and Pass the params
$_fromName = 'From Name';

$_fromEmail = 'Sender Email';

$_recipients='Recipient email'; <!-- can send emails to multiple users by passing emails comma example test1@gmail.com,test2@gmail.com

$_message='<p>This is test email from Bamko </p><p>please dont reply on it</p>';

$_subject = "This is test email";

$_cc = 'cc email';

$_bcc = 'bcc email';
$_email->__sendMail($_fromName, $_fromEmail, $_recipients, $_message, $_subject, $_cc='', $_bcc='');

**********************************************************************************************
