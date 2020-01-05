<?php 

/*
 Modified from UPSTrack Class by Jon Whitcraft <jon_whitcraft@sweetwater.com> 
 
 upsRate class

 It's simple XML ups rate calculation class. I didn't have much time.
 enjoy.

 Chris Lee (http://www.neox.net)
 01/14/2007 - some modification on upsrate.php

*/


//service code to service name mapping is different according to shipping 
//originting country. 
//This table is only used in service code to name conversion to show user
//Empty entry means N/A
//Ex. If shipping origin is Europe, use European Union Origin map.


/*
//From European Union Origin
$ups_service = array ( 
	'01' => '',
       	'02' => '',
	'03' => '',
	'07' => 'UPS Express',
	'08' => 'UPS Expedited',
	'11' => 'UPS Standard',
	'12' => '',
	'13' => '',
	'14' => '',
	'54' => 'UPS Worldwide Express Plus',
	'59' => '',
	'64' => 'UPS Express NA1',
	'65' => 'UPS Express Saver',
   );


//From Canada origin
$ups_service = array ( 
	'01' => 'UPS Express',
       	'02' => 'UPS Expedited',
	'03' => '',
	'07' => 'UPS Worldwide Express',
	'08' => 'UPS Worldwide Expedited',
	'11' => 'UPS Standard',
	'12' => 'UPS 3 Day Select',
	'13' => 'UPS Express Saver',
	'14' => 'UPS Express Early A.M.',
	'54' => 'UPS Worldwide Express Plus',
	'59' => '',
	'64' => '',
	'65' => '',
   );

//For other countries , please refer to UPS development docs.

*/

//US ORIGIN
$ups_service = array ( 
	'01' => 'UPS Next Day Air',
        '02' => 'UPS 2nd Day Air',
	'03' => 'UPS Ground',
	'07' => 'UPS Worldwide Express',
	'08' => 'UPS Worldwide Expedited',
	'11' => 'UPS Standard',
	'12' => 'UPS 3 Day Select',
	'13' => 'UPS Next Day Air Saver',
	'14' => 'UPS Next Day Air Early A.M.',
	'54' => 'UPS Worldwide Express Plus',
	'59' => 'UPS 2nd Day Air A.M.',
	'65' => 'UPS Express Saver',
   );

 
class upsRate
{ 

  

   // You need to create userid ... at http://www.ec.ups.com    
 
    /*var $userid = "bamkoups"; 
    var $passwd = "DIGitrans#4096"; 
    var $accesskey = "ACBEA15A0ED5BA36"; */
	
	var $userid = "philkoosed"; 
    var $passwd = "Bamko1982"; //"Wargon1982"; 
    var $accesskey = "9CBF48F88EB1D9D5"; 

    var $upstool='https://www.ups.com/ups.app/xml/Rate';

    var $request;  //'rate' for single service or 'shop' for all possible services

    var $service;
    var $pickuptype='01'; // 01 daily pickup
      /* Pickup Type
	01- Daily Pickup
	03- Customer Counter
	06- One Time Pickup
	07- On Call Air
	11- Suggested Retail Rates
	19- Letter Center
	20- Air Service Center
      */
    var $residential;

  //ship from location or shipper
    var $s_zip;
    var $s_state;
    var $s_country;  

  //ship to location
    var $t_zip;
    var $t_state;
    var $t_country;

  //package info
    var $package_type;  // 02 customer supplied package

    var $weight;
    
    var $length;
    var $width;
    var $height;
	var $packages;
    var $packageValue;
    var $packageXML;

    var $error=0;
    var $errormsg;

    var $xmlarray = array(); 

    var $xmlreturndata = ""; 
          
   
    function upsRate($szip,$sstate,$scountry) 
    { 
        // init function 
      $this->s_zip = $szip;
      $this->s_state = $this->getState($szip);
      $this->s_country = $scountry;

    } 

    function construct_request_xml(){
      $xml="<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
   <AccessLicenseNumber>$this->accesskey</AccessLicenseNumber>
   <UserId>$this->userid</UserId>
   <Password>$this->passwd</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<RatingServiceSelectionRequest xml:lang=\"en-US\">
  <Request>
    <TransactionReference>
      <CustomerContext>Rating and Service</CustomerContext>
      <XpciVersion>1.0001</XpciVersion>
    </TransactionReference>
    <RequestAction>Rate</RequestAction> 
    <RequestOption>$this->request</RequestOption> 
  </Request>
  <PickupType>
  <Code>$this->pickuptype</Code>
  </PickupType>
  <Shipment>
    <Shipper>
		<ShipperNumber>7YY449</ShipperNumber>
      <Address>
    <StateProvinceCode>$this->s_state</StateProvinceCode>
    <PostalCode>$this->s_zip</PostalCode>
    <CountryCode>$this->s_country</CountryCode>
      </Address>
    </Shipper>
    <ShipTo>
      <Address>
		<StateProvinceCode>$this->t_state</StateProvinceCode>
		<PostalCode>$this->t_zip</PostalCode>
		<CountryCode>$this->t_country</CountryCode>
      </Address>
    </ShipTo>
    <Service>
    <Code>$this->service</Code>
    </Service>";
	
    if($this->packageValue == 'yes')
    {
    	for($cnt=1;$cnt<=$this->packages;$cnt++)
    	{
            $xml.="<Package>
              <PackagingType>  
                <Code>02</Code>  
              </PackagingType> 
              
              <Dimensions>
              <UnitOfMeasurement>
                <Code>IN</Code>
              </UnitOfMeasurement>
              <Length>$this->length</Length>
              <Width>$this->width</Width>
              <Height>$this->height</Height>
            </Dimensions>
              
              <PackageWeight>
                <Weight>$this->weight</Weight>
              </PackageWeight>
            </Package>";
    	}
    }
    else
    $xml .= $this->packageXML;
	
    $xml.="<ShipmentServiceOptions/>
	<RateInformation>
<NegotiatedRatesIndicator/>
</RateInformation>
  </Shipment>
</RatingServiceSelectionRequest>";
/*echo '<pre class="prettyprint linenums">
    <code class="language-xml">'.htmlspecialchars($xml, ENT_QUOTES).'</code>
</pre>';*/
       return $xml;
    }
    
    function create_package_xml($weight,$length,$width,$height)
    {
        $this->packageXML .= "<Package>
              <PackagingType>  
                <Code>02</Code>  
              </PackagingType> 
              
              <Dimensions>
              <UnitOfMeasurement>
                <Code>IN</Code>
              </UnitOfMeasurement>
              <Length>$length</Length>
              <Width>$width</Width>
              <Height>$height</Height>
            </Dimensions>
              
              <PackageWeight>
                <Weight>$weight</Weight>
              </PackageWeight>
            </Package>";
    }

 
    function rate($service='',$tzip,$tstate='',$tcountry='US',
	$weight,$residential=0,$length,$width,$height,$packages,$packageValue='yes',$packagetype='02') 
    { 
		//echo $service.'=='.$tzip.'==='.$tstate.'==='.$tcountry.'=='.$weight.'==='.$residential.'==='.$length.'==='.$width.'===='.$height.'==='.$packages.'==='.$packageValue.'=='.$packagetype;
	
       if($service=='')
          $this->request = 'shop';
       else
	  $this->request = 'rate';

     	$this->service = $service; 
	$this->t_zip = $tzip;
	$this->t_state= $tstate;
	$this->t_country = $tcountry;
	$this->weight = $weight;
	$this->residential=$residential;
	$this->package_type=$packagetype;
    
    $this->length = $length;
    $this->width = $width;
    $this->height = $height;
	$this->packages = $packages;
    $this->packageValue = $packageValue;
	
        $this->__runCurl(); 
        $this->xmlarray = $this->_get_xml_array($this->xmlreturndata); 
//echo '<pre>'; print_r($this->xmlarray); echo '</pre>';
        //check if error occurred
	if($this->xmlarray==""){
          $this->error=0;
	  $this->errormsg="Unable to retrieve the Shipping info";
	  return NULL;
        }
        $values = $this->xmlarray[RatingServiceSelectionResponse][Response][0];
 
	if($values[ResponseStatusCode] == 0){
	  $this->error=$values[Error][0][ErrorCode];
	  $this->errormsg=$values[Error][0][ErrorDescription];
          return NULL;
        }
	
 	return $this->get_rates();

    } 
    /** 
    * __runCurl() 
    * 
    * This is processes the curl command. 
    * 
    * @access    private 
    */ 
    function __runCurl() 
    { 

	$y = $this->construct_request_xml();
     	//echo $y;
/* 
	// -k : do not check certification for https
 	// -d with @ filename : read post data from the file 
	//curl -k -d @input http://www.neox.net/ups/curltest/t.php


	//generate tmp file name & save data
	$tmpfname = tempnam ("/tmp", "tmpfile");
	$fp = fopen($tmpfname,"w");
	fwrite($fp,$y);
	fclose($fp);

	$cmd = "curl -k -d @$tmpfname ".$this->upstool;
	$fp = popen($cmd,"r");
	while(($tmp=fread($fp,4096))!=null){
	  $output .=$tmp;
	}
	pclose($fp);
	
	unlink($tmpfname);

	$this->xmlreturndata = $output;

        //comment rest of the lines to the end of the function
*/

        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL,"$this->upstool"); /// set the post-to url (do not include the ?query+string here!) 
        curl_setopt ($ch, CURLOPT_HEADER, 0); /// Header control 
        curl_setopt ($ch, CURLOPT_POST, 1);  /// tell it to make a POST, not a GET 
        curl_setopt ($ch, CURLOPT_POSTFIELDS, "$y");  /// put the querystring here starting with "?" 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); /// This allows the output to be set into a variable $xyz 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $this->xmlreturndata = curl_exec ($ch); /// execute the curl session and return the output to a variable $xyz 
        
        //echo "\n<P>:result--------------\n<P>";
	//echo $this->xmlreturndata;
        //curl_errno($ch);
        curl_close ($ch); /// close the curl session
 
    } 
    /** 
    * __get_xml_array($values, &$i) 
    * 
    * This is adds the contents of the return xml into the array for easier processing. 
    * 
    * @access    private 
    * @param    array    $values this is the xml data in an array 
    * @param    int    $i    this is the current location in the array 
    * @return    Array 
    */ 
    function __get_xml_array($values, &$i) 
    { 
      $child = array(); 
      if ($values[$i]['value']) array_push($child, $values[$i]['value']); 
     
      while (++$i < count($values)) 
      { 
        switch ($values[$i]['type']) 
        { 
          case 'cdata': 
            array_push($child, $values[$i]['value']); 
          break; 
     
          case 'complete': 
            $name = $values[$i]['tag']; 
            $child[$name]= $values[$i]['value']; 
            if($values[$i]['attributes']) 
            { 
              $child[$name] = $values[$i]['attributes']; 
            } 
          break; 
     
          case 'open': 
            $name = $values[$i]['tag']; 
            $size = sizeof($child[$name]); 
            if($values[$i]['attributes']) 
            { 
                 $child[$name][$size] = $values[$i]['attributes']; 
                  $child[$name][$size] = $this->__get_xml_array($values, $i); 
            } 
            else 
            { 
                  $child[$name][$size] = $this->__get_xml_array($values, $i); 
            } 
          break; 
     
          case 'close': 
            return $child; 
          break; 
        } 
      } 
      return $child; 
    } 
     
    /** 
    * _get_xml_array($data) 
    * 
    * This is adds the contents of the return xml into the array for easier processing. 
    * 
    * @access    private 
    * @param    string    $data this is the string of the xml data 
    * @return    Array 
    */ 
    function _get_xml_array($data) 
    { 
      $values = array(); 
      $index = array(); 
      $array = array(); 
      $parser = xml_parser_create(); 
      xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
      xml_parse_into_struct($parser, $data, $values, $index); 
      xml_parser_free($parser); 
     
      $i = 0; 
     
      $name = $values[$i]['tag']; 
      $array[$name] = $values[$i]['attributes']; 
      $array[$name] = $this->__get_xml_array($values, $i); 
     
      return $array; 
    } 
     
    /** 
    * get_general_package_info() 
    * 
    * This function parses the big array and returns the general info about the shiped package. 
    * 
    * @access    public 
    * @return    Array 
    */ 
    function get_rates() 
    { 
     // $retArray = array('service'=>'','basic'=>0,'option'=>0,'total'=>0,'days'=>'','time'=>'');
          
        $retArray=array();
 
        $values = $this->xmlarray[RatingServiceSelectionResponse][RatedShipment];
      	$ct = count($values);
        for($i=0;$i<$ct;$i++){
	  $current=&$values[$i];

          $retArray[$i]['service'] = $current[Service][0][Code]; 
          $retArray[$i]['basic'] = $current[TransportationCharges][0][MonetaryValue];
	  	  $retArray[$i]['option'] = $current[ServiceOptionsCharges][0][MonetaryValue];
	  	  $retArray[$i]['total'] = $current[TotalCharges][0][MonetaryValue];
	  	  $retArray[$i]['days'] =  $current[GuaranteedDaysToDelivery];
	  	  $retArray[$i]['time'] = $current[ScheduledDeliveryTime];
		  $retArray[$i]['negotiatedRate'] = $current[NegotiatedRates][0][NetSummaryCharges][0][GrandTotal][0][MonetaryValue];
        }

        unset($values); 
         
        return $retArray; 
    } 
	
	function getState($zipCode)
	{
		
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.urlencode($zipCode); 
		$results = json_decode(file_get_contents($url),1); 
		//die('<pre>'.print_r($results,true)); 
		$parts = array( 
		  'address'=>array('street_number','route'), 
		  'city'=>array('locality'), 
		  'state'=>array('administrative_area_level_1'), 
		  'zip'=>array('postal_code'), 
		); 
		if (!empty($results['results'][0]['address_components'])) { 
		  $ac = $results['results'][0]['address_components']; 
		  foreach($parts as $need=>&$types) { 
			foreach($ac as &$a) { 
			  if (in_array($a['types'][0],$types)) $address_out[$need] = $a['short_name']; 
			  elseif (empty($address_out[$need])) $address_out[$need] = ''; 
			} 
		  } 
		} //else echo 'empty results'; 
		return $address_out['state']; 
	}
	
	function getTimeInTransit()
	{
		$upsXpciVersion = "1.0002";
        $description    = "UPS Time In Transit";
        $req_action     = "TimeInTransit";
		$ups_pickupdate = date("Ymd"); //pickupdate is NOW
		
		$data .= "
            <?xml version=\"1.0\" ?>
            <AccessRequest xml:lang=\"en-US\">
                <AccessLicenseNumber>".$this->accesskey."</AccessLicenseNumber>
                <UserId>".$this->userid."</UserId>
                <Password>".$this->passwd."</Password>
            </AccessRequest>

            <?xml version=\"1.0\"?>
            <TimeInTransitRequest xml:lang=\"en-US\">
               <Request>
                  <TransactionReference>
                     <CustomerContext>$description</CustomerContext>
                     <XpciVersion>$upsXpciVersion</XpciVersion>
                  </TransactionReference>
                  <RequestAction>$req_action</RequestAction>
               </Request>
               <TransitFrom>
                  <AddressArtifactFormat>
                    <PoliticalDivision2>".$this->from_city."</PoliticalDivision2>
                    <PoliticalDivision1>".$this->s_state."</PoliticalDivision1>

                     <CountryCode>".$this->s_country."</CountryCode>
                     <PostcodePrimaryLow>".$this->s_zip."</PostcodePrimaryLow>
                  </AddressArtifactFormat>
               </TransitFrom>
               <TransitTo>
                  <AddressArtifactFormat>
                     <CountryCode>".$this->t_country."</CountryCode>
                     <PostcodePrimaryLow>".$this->t_zip."</PostcodePrimaryLow>
                  </AddressArtifactFormat>
               </TransitTo>
			   
			   <ShipmentWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>$this->weight</Weight>
        </ShipmentWeight>
			   ".
/*
               <ShipmentWeight>
                  <UnitOfMeasurement>
                     <Code>$shipment_units_weight_units</Code>
                     <Description>$shipment_units_weight_descript</Description>
                  </UnitOfMeasurement>
                  <Weight>$shipment_units_weight_value</Weight>
               </ShipmentWeight>
               <InvoiceLineTotal>
                  <CurrencyCode>$invoice_curr_code</CurrencyCode>
                  <MonetaryValue>$invoice_curr_value</MonetaryValue>
               </InvoiceLineTotal>
                <DocumentsOnlyIndicator/>
*/
               "<PickupDate>$ups_pickupdate</PickupDate>
            </TimeInTransitRequest>";
			
			//return $data;
			$this->data = $data;
			 $ups_server_url = "https://www.ups.com/ups.app/xml/TimeInTransit";
			 $ch = curl_init();                         /// initialize a cURL session
			 curl_setopt ($ch, CURLOPT_URL,$ups_server_url);        /// set the post-to url (do not include the ?query+string here!)
			 curl_setopt ($ch, CURLOPT_HEADER, 0);              /// Header control
			 curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);      /// Use this to prevent PHP from verifying the host (later versions of PHP including 5)
			 curl_setopt ($ch, CURLOPT_POST, 1);                /// tell it to make a POST, not a GET
			 curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);         /// put the query string here starting with "?" 
			 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);          /// This allows the output to be set into a variable $xyz
			 $upsResponse = curl_exec ($ch);                /// execute the curl session and return the output to a variable $xyz
			 curl_close ($ch);                      /// close the curl session
	
			 
			 $resp = $this->XMLParser($upsResponse);
	
			 $response = array();
	
			foreach ($resp as $v)
			{
			
				if ($v['type'] = 'complete' and '' != $v['value']){
					$html_resp[$v['tag']] = $v['value'];
				}
			}
			
			$this->response = $html_resp;
	  
			/*$this->out = "";
			$this->out.= "<table>";
			foreach ($html_resp as $k => $v){
				$this->out.= "<tr>
						<td>$k</td>
						<td>$v</td>
					  </tr>
				   ";
		   $this->out.= "</table>";
		   }*/
		   return $this->response;
	}
	
	function XMLParser($simple) 
    {
        $p = xml_parser_create();
        xml_parser_set_option($p,XML_OPTION_CASE_FOLDING,0);
        xml_parser_set_option($p,XML_OPTION_SKIP_WHITE,1);
        xml_parse_into_struct($p,$simple,$vals,$index);
        xml_parser_free($p);

        return $vals;
    }

} 

 
//example
/*   

$ups = new upsRate('10001','NY','US');


//    function rate($service='',$tzip,$tstate='',$tcountry='US',
//	$weight,$residential=0,$packagetype='02') 


// if first arg is empty, show all possible services
$ok=$ups->rate('','07631','NJ','US',33.2);
//else use service code such as '03' for ground
 



//$data = $ups->xmlarray;   
//print_r($data);

print_r($ups->get_rates());

 */

?>
