<?php
// require_once("../vendor/autoload.php");
require_once("../lib/easypost.php");
if(isset($_POST['btnVerify']))
{
	\EasyPost\EasyPost::setApiKey('cueqNZUb3ldeWTNX7MU3Mel8UXtaAMUi');
	
	try {
		// create address
		$address_params = array("name"    => "'".$_POST['txtName']."'",
								"street1" => "'".$_POST['txtAddress']."'",
								//"street2" => "Apt 20",
								"city"    => "'".$_POST['txtCity']."'",
								"state"   => "'".$_POST['txtState']."' ",
								"zip"     => "'".$_POST['txtZip']."'",
								"country" => "us");
								
	
		$address = \EasyPost\Address::create($address_params);
		/*print_r($address);*/
	
		// retrieve
		$retrieved_address = \EasyPost\Address::retrieve($address->id);
	   /*print_r($retrieved_address);*/
	
		// verify
		$verified_address = $address->verify();
		/*print_r($verified_address);*/
	
		// create and verify at the same time
		$verified_on_create = \EasyPost\Address::create_and_verify($address_params);
		
		$isSuccess   = $verified_on_create->verifications->delivery->success;
		$name        = $verified_on_create->name;
		$street1     = $verified_on_create->street1;
		$street2     = $verified_on_create->street2;
		$city        = $verified_on_create->city;
		$state       = $verified_on_create->state;
		$zip         = $verified_on_create->zip;
		$country     = $verified_on_create->country;
		$residential = $verified_on_create->residential;
		$latitude    = $verified_on_create->verifications->delivery->details->latitude;
		$longitude   = $verified_on_create->verifications->delivery->details->longitude;
	
		// all
		// $all = \EasyPost\Address::all();
		//print_r($all);
	
	} 
	catch (Exception $e) 
	{
		echo "Status: " . $e->getHttpStatus() . ":\n";
		echo $e->getMessage();
		if (!empty($e->param)) 
		{
			echo "\nInvalid param: {$e->param}";
		}
		exit();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<style type="text/css">
.tdClass
{
	padding:10px;
	width:100%;
}
.txtBox
{
	padding:5px;
}
</style>
<body>

	<form method="post" autocomplete="off">
        <table cellpadding="0" cellspacing="0" width="100%" align="center">
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="text" name="txtName" id="txtName" placeholder="Enter Name" value="<?php echo $_POST['txtName'] ?>" required/>
                </td>
            </tr>
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="text" name="txtAddress" id="txtAddress" placeholder="Enter Street Address" value="<?php echo $_POST['txtAddress'] ?>" required/>
                </td>
            </tr>
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="text" name="txtCity" id="txtCity" placeholder="Enter City" value="<?php echo $_POST['txtCity'] ?>" required/>
                </td>
            </tr>
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="text" name="txtState" id="txtState" placeholder="Enter State" value="<?php echo $_POST['txtState'] ?>" required/>
                </td>
            </tr>
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="text" name="txtZip" id="txtZip" placeholder="Enter Zip" value="<?php echo $_POST['txtZip'] ?>" required/>
                </td>
            </tr>
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="text" name="txtCountry" id="txtCountry" placeholder="Enter Country" value="US" required disabled="disabled"/>
                </td>
            </tr>
            
            <tr>
                <td class="tdClass">
                    <input class="txtBox" type="submit" name="btnVerify" id="btnVerify" value="verify" required/>
                </td>
            </tr>
            
        </table>
    </form>
	
    <?php if($isSuccess == 1) { ?>
    <table cellpadding="0" cellspacing="0" width="100%" align="center">
    	<tr>
        	<td>
            	<strong> <?php echo $isSuccess.'==='; ?>NAME : </strong> <?php echo $name; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> STREET 1 : </strong> <?php echo $street1; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> STREET 2 : </strong> <?php echo $street2; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> CITY : </strong> <?php echo $city; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> STATE : </strong> <?php echo $state; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> ZIP CODE : </strong> <?php echo $zip; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> RESIDENTIAL : </strong> <?php if($residential == 1) { echo 'YES'; } else { echo 'NO'; } ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> LATITUDE : </strong> <?php echo $latitude; ?>
            </td>
        </tr>
        
        <tr>
        	<td>
            	<strong> LONGITUDE : </strong> <?php echo $longitude; ?>
            </td>
        </tr>
    </table>
    <?php } ?>
</body>
</html>


