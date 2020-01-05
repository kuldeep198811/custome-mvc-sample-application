<?php
namespace library\email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use core\BaseController;

/**
* @description: Email Class for sedning simple email and smtp EMail
*/

Class email{

	private $_mail;

    private $from_email_name;
	private $from_email_address;
    public function __construct()
	{
		 $_baseControllerObject=new BaseController();
	     $_getData= $_baseControllerObject->__storeDetail();
		 
    	 $this->from_email_name= isset($_getData[0]['from_email_name'])?$_getData[0]['from_email_name']:'';
		 $this->from_email_address=isset($_getData[0]['from_email_address'])?$_getData[0]['from_email_address']:'noreply@domain.net';
	}
	
	/**
	 * @description:Send SMTP EMail(return bool)
	 */

	function __sendMailStmp($_from_name = "", $_from_email = "", $_subject, $_message, $_recipients = array(), $_cc = array(), $_bcc = array(), $_attachments = array(),$_config=array())
	{

		// cal smtp config function
		$this->_config	=	$_config;
		$this->__smtpConfig();

		 #from
		$this->_mail->setFrom($this->from_email_address, $this->from_email_name);

		#bind recipients
		if(is_array($_recipients) && !empty($_recipients)){
			foreach($_recipients as $_recipient){
			    $_recipient	=	html_entity_decode($_recipient, ENT_QUOTES);
				$this->_mail->addAddress($_recipient);     // Add a recipient
			}
		}

		#bind cc
		if(is_array($_cc) && !empty($_cc_)){
			foreach($_cc as $_cc_){
				$this->_mail->AddCC($_cc_);     // Add a cc
			}
		}


		#bind bcc
		if(is_array($_bcc) && !empty($_bcc)){
			foreach($_bcc as $_bcc_){
				$this->_mail->AddBCC($_bcc_);     // Add a bcc
			}
		}


		#attachment
		if(is_array($_attachments) && !empty($_attachments)){
			foreach($_attachments as $_attachment){
				$this->_mail->addAttachment($_attachment); //Add attachment
			}
		}

		#Content
		$this->_mail->isHTML(true);  // Set email format to HTML
		$this->_mail->Subject = $_subject;
		$this->_mail->Body    = $_message;

		if($this->_mail->send()){

			return true;
		}
		else{

			return false;
		}

	}
	/*
	* @description:Load smptp configuration for sending smtp EMail
	*/
	function __smtpConfig(){

		require 'vendor/autoload.php';

		$this->_mail = new PHPMailer(TRUE);

		//Change SMTP configuration

		if(is_array($this->_config) && !empty($this->_config)){

			$_host=$this->_config['host'];

			$_username=$this->_config['username'];

			$_password=$this->_config['password'];

		}else{

			
            $_host='smtp.XXXXXXXXXXXXXXXX.com';
            
			$_username='customerservice@XXXXXXXXXXX.com';

			$_password='XXXXXXXXXXXXXXXXXXXXx';
			
		}
		
		$this->_mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

		$this->_mail->SMTPDebug = 0;                         // Enable verbose debug output

		$this->_mail->isSMTP();                              // Set mailer to use SMTP

		$this->_mail->Host = $_host; 					 	 // Specify main and backup SMTP servers

		$this->_mail->SMTPAuth = true;                       // Enable SMTP authentication

		$this->_mail->Username = $_username;                 // SMTP username

		$this->_mail->Password = $_password;                 // SMTP password

		$this->_mail->SMTPSecure = 'tls';                    // Enable TLS encryption, `ssl` also accepted

		$this->_mail->Port = 587;
	}

	/**
	 * @description:Send Simple EMail (return bool)
	 * 
	 */
	function __sendMail($_fromName, $_fromEmail, $_recipient, $_mail_body, $_subject, $_cc='', $_bcc='')
	{
		
		$_fromName = $this->from_email_name;
		$_fromEmail = $this->from_email_address;
		 
		return $this->__sendMailStmp($_fromName, $_fromEmail, $_subject, $_mail_body, [$_recipient]);
		
	}

}
?>
