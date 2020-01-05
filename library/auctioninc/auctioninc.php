<?php
namespace library\auctioninc;
class auctioninc{
/*
 *
 * Copyright (c) Paid, Inc.  All rights reserved.
 *
 * $Id: ShipRateAPIExample.php,v 1.2 2005/05/27 17:03:56 jmartin Exp $
 *
 * This program demonstrates the use of the AuctionInc Shipping API.
 *
 */
 public function __calculateShip($_destCountryCode,$_destPostalCode){
   //
   // Please enter your account Id that you receive when you register at AuctionInc site
   //
   $_API_AccountId = '8558d5409ddab15fa9e10c9137d06abb';
 
   //
   // Change the path to directory where you have installed the ShipRate API files
   // 
   $_shipAPIClass = 'ShipRateAPI.php';
   
   
   if (! file_exists(dirname(__FILE__).'/'.$_shipAPIClass)) die("Unable to locate _shipAPI class file [$_shipAPIClass]");
   include(dirname(__FILE__).'/'.$_shipAPIClass);

   // _data used to initialize the rate engine with various carriers/services
   $_carrierList = array(
      'UPS'   => 'R',     // Retail _entryPoint
      'USPS'  => 'M',     // Manual (no discount for electronic delivery confirmation
      'FEDEX' => 'C'      // Request Courier  
      );
      
   $_serviceList = array(
      'UPSGND' => array( 'carrier' => 'UPS'),
      'UPS3DS' => array( 'carrier' => 'UPS'),
      'UPS2DA' => array( 'carrier' => 'UPS'),
      'UPSNDS' => array( 'carrier' => 'UPS'),
      'UPSNDA' => array( 'carrier' => 'UPS'),
	  'UPSEX'  => array( 'carrier' => 'UPS'),
	  'UPSES'  => array( 'carrier' => 'UPS'),
      'FDXGND' => array( 'carrier' => 'FEDEX'),
      'FDX2D'  => array( 'carrier' => 'FEDEX'),
      'FDXES'  => array( 'carrier' => 'FEDEX'),
      'FDXFO'  => array( 'carrier' => 'FEDEX'),
      'USPPP'  => array( 'carrier' => 'USPS'),
      'USPPM'  => array( 'carrier' => 'USPS'),
      'USPMM'  => array( 'carrier' => 'USPS', '_onDemand'=> true),
      'USPBPM' => array( 'carrier' => 'USPS', '_onDemand'=> true)
      );
         
   // User input for origin/destination addresses
   $_origCountryCode = 'US';         // Presently only US origins supported
   $_origPostalCode  = '90010';
   //$_destCountryCode = 'US';
   //$_destPostalCode  = '80022';
   $_residential     = true;

   // Item information
   $_weight = 20;
   $_length = 10;
   $_width = 8;
   $_height = 6.5;
   $_declVal = 395.00;
   $_packMethod = 'T';      // T)ogether or S)eparate
   $_odservices = "UPSGNDUPS3DS, UPS2DA, UPSNDS, UPSEX,UPSES, UPSNDA,FDXGND,FDX2D,FDXES,FDXFO";  // this item qualifies for these on-demand services

   // Instantiate the Shipping Rate API interface
   // NOTE: 
   $_shipAPI = new ShipRateAPI($_API_AccountId);

   // We dont currently support SSL
   $_shipAPI->setSecureComm(false);

   $_shipAPI->setDestinationAddress($_destCountryCode, $_destPostalCode, '', $_residential);
   
   // Setup Carriers and Services
   foreach($_carrierList AS $_code => $_entryPoint) {
      $_shipAPI->addCarrier($_code, $_entryPoint);
   }
   foreach($_serviceList AS $_scode => $_data) {
      $_onDemand = isset($_data['_onDemand']) ? $_data['_onDemand'] : false;
      // Add service to the API 
      $_shipAPI->addService($_data['carrier'], $_scode, $_onDemand);
   }      
   
   // Add _data on one item
   $_shipAPI->addItemCalc('referenceNo1', 1, $_weight, 'LBS', $_length, $_width, $_height, 'IN',  $_declVal, $_packMethod);
   $_shipAPI->addItemOnDemandServices($_odservices);
  
   $ok = $_shipAPI->GetItemShipRateSS( $_shipRates );
   if ($ok) {
      return $_shipRates;

       //$_htm.= "<p>Example FORM SELECT Input:<br><form>" .$this->__genSelectHTML($_shipRates) . '</form>';
   } else {
      return 'Sorry but we were unable to determine shipping rates';
   }

   // ------------------------------------------------------------------------
   // Now let's try the GetItemShipRateXS call
   // ------------------------------------------------------------------------
   // XS uses origin addresses
   // $_shipAPI->addOriginAddress($_origCountryCode, $_origPostalCode, '');
   
   // $_htm.= '<h2>GetItemShipRatesSS Method</h2>';
   // $_htm.= 'This method determines shipping rates using shipping preferences that are passed in XML<p>';
   // $_ok = $_shipAPI->GetItemShipRateXS( $_shipRates );
   // if (! $_ok) {
   //    //$_htm.= 'Sorry but we were unable to determine shipping rates';
   // } else {
   //    return $_shipRates;
   //    //displayRates($_shipRates);
   //     $_htm.= "<p>Example FORM SELECT Input:<br><form>" . $this->__genSelectHTML($_shipRates) . '</form>';
   // }  
   
}

   /**
   * Displays shipping rates in a table just to show the results
   * @param    assocArray     Array of results from the Shipping Rates API
   * @param    string         name for the SELECT form element (default 'shiprate')
   * @param    string         format of the VALUE for the SELECT that is returned in the POST (see Format below)
   * @param    string         format of the display for each SELECT option (see Format below)
   * @param    int            number of rows that the SELECT will display automatically (default 1)
   * @param    string         class name to use in the SELECT if you are using CSS default (none)
   * @param    string         option value when there are no rates (default '0')
   * @param    string         message to display if no rates are available (default 'Unable to determine')
   * @return   void
   *
   * NOTE:  Formating of the SELECT values and display are controlled by supplying strings with tags that will
   *        be replaced by the function with the appropriate text.  The tags are as follows:
   *          Tag - Value
   *           R - the shipping rate unformatted
   *           F - the shipping rate formatted (i.e. $12.95) 'Free' is displayed when rate is zero (0)
   *           S - the service _code
   *           C - carrier _code
   *           N - service name (i.e. 'UPS Ground')
   *           
   *        An example format string 'N - F' will be displayed like 'UPS Ground - $12.50'
   **/
   function __genSelectHTML($_shipRates, $_fieldName='shiprate', $_valueFormat='C,R', $_displayFormat='N - F', $_size=4, $_class='', $_noRatesVal='0', $_noRatesMsg='Unable to determine') {
         
      $_html = "<SELECT NAME=\"$_fieldName\"" . (! empty($_class) ? " CLASS=\"$_class\"" : '') . " SIZE=\"$_size\">\n";
      $_c=sizeof($_shipRates['ShipRate']);

      $_n=0;    // the number of valid rates
      for($_i=0; $_i < $_c; ++$_i) {
         $_valid = $_shipRates['ShipRate'][$_i]['Valid'];
         if (strcmp($_valid, 'true') !== 0) continue;
         ++$_n;
         
         $_vals['R'] = $_shipRates['ShipRate'][$_i]['Rate'];
         $_vals['F'] = $_shipRates['ShipRate'][$_i]['Rate'] > 0 ? ('$' . number_format($_shipRates['ShipRate'][$_i]['Rate'],2) ) : 'Free';
         $_vals['S'] = $_shipRates['ShipRate'][$_i]['ServiceCode'];
         $_vals['C'] = $_shipRates['ShipRate'][$_i]['CarrierCode'];
         $_vals['N'] = $_shipRates['ShipRate'][$_i]['ServiceName'];

         // Iterate over the format strings and substitute the tags with the appropriate values
         $_value = '';
         for($_s=0, $_l=strlen($_valueFormat); $_s < $_l; ++$_s) {
            $_char = $_valueFormat{$_s};
            $_value .= urlencode( isset($_vals[$_char]) ? $_vals[$_char] : $_char );
         }

         $_display = '';
         for($_s=0, $_l=strlen($_displayFormat); $_s < $_l; ++$_s) {
            $_char = $_displayFormat{$_s};
            $_display .= isset($_vals[$_char]) ? $_vals[$_char] : $_char;
         }
         
         $_selected = $_n==1 ? ' SELECTED' : '';
         $_html .= "<OPTION VALUE=\"$_value\"$_selected>$_display\n";
      }
      
      if ($_n == 0) {
         $_html .= "<OPTION VALUE=\"$_noRatesVal\">$_noRatesMsg\n";
      }
      $_html .=  '</SELECT>';
      
      return $_html;
   }


   

	public function __getShippingOptions($rsl_shipper_location,$rsl_order_shipping,$total_weight = 0,$final_sub_total = 0){
		$_API_AccountId = '8558d5409ddab15fa9e10c9137d06abb';
		$_shipAPIClass = 'ShipRateAPI.php';
		if (! file_exists(dirname(__FILE__).'/'.$_shipAPIClass)) die("Unable to locate _shipAPI class file [$_shipAPIClass]");
		include(dirname(__FILE__).'/'.$_shipAPIClass);

		// _data used to initialize the rate engine with various carriers/services
		$_carrierList = array(
			'UPS'   => 'R',     // Retail _entryPoint
			'USPS'  => 'M',     // Manual (no discount for electronic delivery confirmation
			'FEDEX' => 'C'      // Request Courier  
		);

		$_serviceList = array(
			'UPSGND' => array( 'carrier' => 'UPS'),
			'UPS3DS' => array( 'carrier' => 'UPS'),
			'UPS2DA' => array( 'carrier' => 'UPS'),
			'UPSNDS' => array( 'carrier' => 'UPS'),
			'UPSNDA' => array( 'carrier' => 'UPS'),
			'FDXGND' => array( 'carrier' => 'FEDEX'),
			'FDX2D'  => array( 'carrier' => 'FEDEX'),
			'FDXES'  => array( 'carrier' => 'FEDEX'),
			'FDXFO'  => array( 'carrier' => 'FEDEX'),
			'USPPP'  => array( 'carrier' => 'USPS'),
			'USPPM'  => array( 'carrier' => 'USPS'),
			'USPMM'  => array( 'carrier' => 'USPS', 'ondemand'=> true),
			'USPBPM' => array( 'carrier' => 'USPS', 'ondemand'=> true),
			'UPSCAN' => array('carrier' => 'UPS'),
			'UPSWEP' => array('carrier' => 'UPS'),
			'UPSWSV' => array('carrier' => 'UPS'),
			'UPSWEX' => array('carrier' => 'UPS')
		);

		// User input for origin/destination addresses
		$_origCountryCode = strtoupper($rsl_shipper_location['shipper_country']);
		$_origPostalCode  = $rsl_shipper_location['shipper_zip'];

		$_destCountryCode = $rsl_order_shipping['countrycode'];
		$_destPostalCode  = $rsl_order_shipping['zipcode'];
		$_residential     = true;

		// Item information
		$_weight = $total_weight;
		$_length = 1;
		$_width = 1;
		$_height = 1;
		$_declVal = 0;
		$_packMethod = 'T';      // T)ogether or S)eparate
		$_odservices = "UPSGND, UPS3DS, UPS2DA, UPSNDS, UPSNDA, FDXGND, FDX2D, FDXES, FDXFO, USPPP, USPPM, USPMM, USPBPM";  // this item qualifies for these on-demand services

		// Instantiate the Shipping Rate API interface
		// NOTE: 
		$_shipAPI = new ShipRateAPI($_API_AccountId);

		// We dont currently support SSL
		$_shipAPI->setSecureComm(false);

		$_shipAPI->setDestinationAddress($_destCountryCode, $_destPostalCode, '', $_residential);

		// Setup Carriers and Services
		foreach($_carrierList AS $_code => $_entryPoint) {
			$_shipAPI->addCarrier($_code, $_entryPoint);
		}
		foreach($_serviceList AS $_scode => $_data) {
			$_onDemand = isset($_data['_onDemand']) ? $_data['_onDemand'] : false;
			// Add service to the API 
			$_shipAPI->addService($_data['carrier'], $_scode, $_onDemand);
		}      

		// Add _data on one item
		$_shipAPI->addItemCalc('referenceNo1', 1, $_weight, 'LBS', $_length, $_width, $_height, 'IN',  $_declVal, $_packMethod);
		$_shipAPI->addItemOnDemandServices($_odservices);

		$ok = $_shipAPI->GetItemShipRateSS( $_shipRates );
		if ($ok) {

		} else {
			echo'Sorry but we were unable to determine shipping rates';
		}
		$_shipAPI->addOriginAddress($_origCountryCode, $_origPostalCode, '');




		$_shipAPI->GetItemShipRateXS( $_shipRates );
		$ups_fedex_rates = array();

		$get_shipping_service_type_array = array();
		$get_selected_service_type	= $rsl_shipper_location;

		$services_type = "UPS";
		if($get_selected_service_type['shipper_type'] == 'fedex')
		{
			$services_type = "Fedex";
		}
		else if($get_selected_service_type['shipper_type'] == 'ups_and_fedex')
		{
			$services_type = "UPS & Fedex";
		}

		if (! $ok) {
			//echo 'Sorry but we were unable to determine shipping rates';
			$shippingRates["shipping_error"] = 'We were unable to calculate a shipping rate. This might be because the connection to '.$services_type.' has a temporary malfunction or the address you supplied might not be recognized by '.$services_type.'. Shipping charges will be added to your order total upon fulfillment or call for pricing.';
		} else {
			$shippingRates = $_shipRates;				
		} 

		return $shippingRates;
	}
}

/*$_ship=new auctioninc();
$_shipOption=$_ship->__calculateShip('US',96797);
print "<pre>";print_r($_shipOption); print "</pre>";*/
    
?>