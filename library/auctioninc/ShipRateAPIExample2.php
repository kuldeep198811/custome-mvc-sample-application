<?php
/*
 *
 * Copyright (c) Paid, Inc.  All rights reserved.
 *
 * $Id: ShipRateAPIExample.php,v 1.2 2005/05/27 17:03:56 jmartin Exp $
 *
 * This program demonstrates the use of the AuctionInc Shipping API.
 *
 */

   //
   // Please enter your account Id that you receive when you register at AuctionInc site
   //
   $API_AccountId = '8558d5409ddab15fa9e10c9137d06abb';
 
   //
   // Change the path to directory where you have installed the ShipRate API files
   // 
   $shipAPIClass = 'ShipRateAPI.inc';
   
   
   if (! file_exists($shipAPIClass)) die("Unable to locate ShipAPI class file [$shipAPIClass]");
   include($shipAPIClass);

   // Data used to initialize the rate engine with various carriers/services
   $carrierList = array(
      'UPS'   => 'R',     // Retail Entrypoint
      'USPS'  => 'M',     // Manual (no discount for electronic delivery confirmation
      'FEDEX' => 'C'      // Request Courier  
      );
      
   $serviceList = array(
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
      'USPMM'  => array( 'carrier' => 'USPS', 'ondemand'=> true),
      'USPBPM' => array( 'carrier' => 'USPS', 'ondemand'=> true)
      );
         
   // User input for origin/destination addresses
   $origCountryCode = 'US';         // Presently only US origins supported
   $origPostalCode  = '90010';
   $destCountryCode = 'US';
   $destPostalCode  = '80022';
   $residential     = true;

   // Item information
   $weight = 20;
   $length = 10;
   $width = 8;
   $height = 6.5;
   $declVal = 395.00;
   $packMethod = 'T';      // T)ogether or S)eparate
   $odservices = "UPSGND, UPS3DS, UPS2DA, UPSNDS, UPSEX,UPSES, UPSNDA,FDXGND,FDX2D,FDXES,FDXFO";  // this item qualifies for these on-demand services

   // Instantiate the Shipping Rate API interface
   // NOTE: 
   $shipAPI = new ShipRateAPI($API_AccountId);

   // We dont currently support SSL
   $shipAPI->setSecureComm(false);

   $shipAPI->setDestinationAddress($destCountryCode, $destPostalCode, '', $residential);
   
   // Setup Carriers and Services
   foreach($carrierList AS $code => $entryPoint) {
      $shipAPI->addCarrier($code, $entryPoint);
   }
   foreach($serviceList AS $scode => $data) {
      $onDemand = isset($data['ondemand']) ? $data['ondemand'] : false;
      // Add service to the API 
      $shipAPI->addService($data['carrier'], $scode, $onDemand);
   }      
   
   // Add data on one item
   $shipAPI->addItemCalc('referenceNo1', 1, $weight, 'LBS', $length, $width, $height, 'IN',  $declVal, $packMethod);
   $shipAPI->addItemOnDemandServices($odservices);
     
   echo '<h2>GetItemShipRatesSS Method</h2>';
   echo 'This method utilizes shipping preferences that are pre-configured at AuctionInc website<p>';
   
   $ok = $shipAPI->GetItemShipRateSS( $shipRates );
   if ($ok) {
      displayRates($shipRates);
   } else {
      echo 'Sorry but we were unable to determine shipping rates';
   }

   // ------------------------------------------------------------------------
   // Now let's try the GetItemShipRateXS call
   // ------------------------------------------------------------------------
   // XS uses origin addresses
   $shipAPI->addOriginAddress($origCountryCode, $origPostalCode, '');
   
   echo '<h2>GetItemShipRatesSS Method</h2>';
   echo 'This method determines shipping rates using shipping preferences that are passed in XML<p>';
   $ok = $shipAPI->GetItemShipRateXS( $shipRates );
   if (! $ok) {
      echo 'Sorry but we were unable to determine shipping rates';
   } else {
      displayRates($shipRates);
   }  
   
   /**
   * Displays shipping rates in a table just to show the results
   * @param    assocArray     Array of results from the Shipping Rates API
   * @return   void
   **/
   function displayRates($shipRates) {
         
      echo "<table border=0><tr><th>Valid</th><th>CarrierCode</th><th>ServiceCode</th><th>ServiceName</th><th>CalcMethod</th><th>Rate</th>";
         for($i=0, $c=sizeof($shipRates['ShipRate']); $i < $c; $i++) {
            $valid = $shipRates['ShipRate'][$i]['Valid'];
            $carrierCode = $shipRates['ShipRate'][$i]['CarrierCode'];
            $serviceCode = $shipRates['ShipRate'][$i]['ServiceCode'];
            $serviceName = $shipRates['ShipRate'][$i]['ServiceName'];
            $calcMethod = $shipRates['ShipRate'][$i]['CalcMethod'];
            $rate = $shipRates['ShipRate'][$i]['Rate'];
            
            echo "<tr><td>$valid</td><td>$carrierCode</td><td>$serviceCode</td><td>$serviceName</td><td>$calcMethod</td><td>$rate</td></tr>";
         }
         echo "</table>";
         
         echo "<p>Example FORM SELECT Input:<br><form>" . genSelectHTML($shipRates) . '</form>';
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
   *           S - the service code
   *           C - carrier code
   *           N - service name (i.e. 'UPS Ground')
   *           
   *        An example format string 'N - F' will be displayed like 'UPS Ground - $12.50'
   **/
   function genSelectHTML($shipRates, $fieldName='shiprate', $valueFormat='C,R', $displayFormat='N - F', $size=4, $class='', $noRatesVal='0', $noRatesMsg='Unable to determine') {
         
      $html = "<SELECT NAME=\"$fieldName\"" . (! empty($class) ? " CLASS=\"$class\"" : '') . " SIZE=\"$size\">\n";
      $c=sizeof($shipRates['ShipRate']);

      $n=0;    // the number of valid rates
      for($i=0; $i < $c; ++$i) {
         $valid = $shipRates['ShipRate'][$i]['Valid'];
         if (strcmp($valid, 'true') !== 0) continue;
         ++$n;
         
         $vals['R'] = $shipRates['ShipRate'][$i]['Rate'];
         $vals['F'] = $shipRates['ShipRate'][$i]['Rate'] > 0 ? ('$' . number_format($shipRates['ShipRate'][$i]['Rate'],2) ) : 'Free';
         $vals['S'] = $shipRates['ShipRate'][$i]['ServiceCode'];
         $vals['C'] = $shipRates['ShipRate'][$i]['CarrierCode'];
         $vals['N'] = $shipRates['ShipRate'][$i]['ServiceName'];

         // Iterate over the format strings and substitute the tags with the appropriate values
         $value = '';
         for($s=0, $l=strlen($valueFormat); $s < $l; ++$s) {
            $char = $valueFormat{$s};
            $value .= urlencode( isset($vals[$char]) ? $vals[$char] : $char );
         }

         $display = '';
         for($s=0, $l=strlen($displayFormat); $s < $l; ++$s) {
            $char = $displayFormat{$s};
            $display .= isset($vals[$char]) ? $vals[$char] : $char;
         }
         
         $selected = $n==1 ? ' SELECTED' : '';
         $html .= "<OPTION VALUE=\"$value\"$selected>$display\n";
      }
      
      if ($n == 0) {
         $html .= "<OPTION VALUE=\"$noRatesVal\">$noRatesMsg\n";
      }
      $html .=  '</SELECT>';
      
      return $html;
   }
    
?>