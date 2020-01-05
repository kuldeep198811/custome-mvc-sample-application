<?php
namespace library\auctioninc;
/*
 *
 * Copyright (c) Paid, Inc.  All rights reserved.
 *
 * $Id: ShipRateAPI.inc,v 1.10 2007/04/20 18:17:39 dsherman Exp $
 *
 * This program is part of the Paid ShipRateAPI Toolkit and is the interface class for PHP client applications 
 * that want to make calls to the rate engine.  
 *
 */

$incPath = dirname(__FILE__);
include_once("ShipRateSocketPost.inc");
include_once("ShipRateParserXML.inc");
include_once("ShipRateParserXMLItem.inc");

/**
 * API Main Class
 *
 * @author "Victor Didovicher" <victord@paid.com>
 * @date 2005.05.17
 * @version 1.0
 * @copyright (c) Paid, Inc.  All rights reserved.
 */
class ShipRateAPI
{
   var $rateReq;  
   var $results;
   var $accountId;            // Access Key to Web API
   var $refCode;                // Reference code that is logged with each call - used for auditing when 
                              // integrator is working with different customers
   var $secureComm = true;    // Flag to control if Web Service Calls are secure or not
   var $debugComm = false;    // Flag if communications should output debug information
   var $apiURL;	              // if set allows client to override the URL to the web service

   /**
   * Constructor
   * @param    string   API account Id
   * @param    string   Reference Code  that is logged with each call (optional)
   * @return   void
   **/
   function __construct( $accountId, $refCode='' )
   {
      $this->accountId = $accountId;
      $this->refCode = $refCode;
      $this->reset();
   }

   /**
    * Generates the GetItemShipRateXS XML and invokes the web service method
    * @param     (ref)Array   will be populated with and array of ShipRate assoc arrays
    * @return    boolean      true indicates success, false indicates an error occurred
    * @access public
    */
   function GetItemShipRateXS(& $shipRateArray )
   {
      $reqXML = $this->_makeGetItemShipRateXS_XML();
      return $this->_GetItemShipRate($reqXML, $shipRateArray);
   }

   /**
    * Generates the GetItemShipRateSS XML and invokes the web service method
    * @param     (ref)Array   will be populated with and array of ShipRate assoc arrays
    * @return    boolean      true indicates success, false indicates an error occurred
    * @access    public
    */
   function GetItemShipRateSS(& $shipRateArray )
   {
      $reqXML = $this->_makeGetItemShipRateSS_XML();
      return $this->_GetItemShipRate($reqXML, $shipRateArray);
   }

   /**
    * Internal method used by the GetItemShipRate methods which does the actual call out to the web service
    * @param      (ref)Array   will be populated with and array of ShipRate assoc arrays
    * @return     boolean      true indicates success, false indicates an error occurred
    * @access     private
    */
   function _GetItemShipRate( $reqXML, & $shipRateArray )
   {
      $post = new ShipRateSocketPost();
      
      // If they overrode the secure mode, we'll set the port to 80
      if (! $this->secureComm) $post->setPort(80);
      if (isset($this->apiURL)) $post->setURL($this->apiURL);
      $post->setDebug($this->debugComm);
      
      if ($post->post($reqXML, false, $respXML, $headers, $errorMsg)) { 
         $p = new ShipRateParserXMLItem();
         $shipRateArray = $p->parse($respXML);
         return (! isset($shipRateArray['ErrorList']) ); 
      } else {
         // Set an error
         $shipRateArray = $this->_createError(505, $errorMsg);
         return false;
      }
   }
   
   /**
   * Used to override the default URL to the Web Service API
   * @param    string	url to web service
   * @return   void
   * @access   public
   **/
   function setURL( $url ) {
      $this->apiURL = $url;
   }
   
   /**
   * Used to control if the communications to the web service is secure or not
   * @param    bool     true (default) will result in calls being invoked via SSL
   * @return   void
   * @access   public
   **/
   function setSecureComm( $trueFalse ) {
      $this->secureComm = is_bool($trueFalse) ? $trueFalse : true;
   }
   
   /**
   * Used to control if the communications should echo out debug information
   * @param    bool     true will enable debug information, (default false)
   * @return   void
   * @access   public
   **/
   function setDebugComm( $trueFalse ) {
      $this->debugComm = is_bool($trueFalse) ? $trueFalse : false;
   }
   
   /**
    * Reinitialize the class to initial state
    * @access public
    */
   function reset()
   {
      $this->rateReq = array();
      $this->rateReq['DestinationAddress'] = array();
      $this->rateReq['OriginAddressList'] = array();
      $this->rateReq['ItemList'] = array();
      $this->rateReq['CarrierList'] = array();
      $this->rateReq['Currency'] = 'USD';
      $this->setAccountId($this->accountId);
   }

   /**
    * Set Currency
    * @param String - Currency (USD)
    * @access public
    */
   function setCurrency($v) { $this->rateReq['Currency'] = strtoupper($v); }

   /**
    * Set ShipRate API Account ID
    * @param String - You PAID ShipAPI Account ID
    * @access public
    */
   function setAccountId($id) { $this->rateReq['AccountID'] = $id; }

   /**
    * Add Origin Address
    * @param String - Postal Code
    * @param String - State or Province Code (2 Letter)
    * @param String - Country Code (2 Letter)
    * @param String - Origin Code
    * @access public
    */
   function addOriginAddress($countryCode, $postalCode, $stateOrProvinceCode='', $originCode='')
   {
      $cnt = (int) count($this->rateReq['OriginAddressList']);
      $this->rateReq['OriginAddressList'][$cnt] = array();
      $this->rateReq['OriginAddressList'][$cnt]['PostalCode'] = strtoupper($postalCode);
      $this->rateReq['OriginAddressList'][$cnt]['StateOrProvinceCode'] = strtoupper($stateOrProvinceCode);
      $this->rateReq['OriginAddressList'][$cnt]['CountryCode'] = strtoupper($countryCode);
      $this->rateReq['OriginAddressList'][$cnt]['OriginCode'] = $originCode;
   }

   /**
    * Set Destination Address
    * @param String - Postal Code
    * @param String - State or Province Code (2 Letter)
    * @param String - Country Code (2 Letter)
    * @param Bool - Residential Falg
    * @access public
    */
   function setDestinationAddress($countryCode, $postalCode, $stateOrProvinceCode='', $residentialFlag=false)
   {
      $this->rateReq['DestinationAddress']['PostalCode'] = strtoupper($postalCode);
      $this->rateReq['DestinationAddress']['StateOrProvinceCode'] = strtoupper($stateOrProvinceCode);
      $this->rateReq['DestinationAddress']['CountryCode'] = strtoupper($countryCode);
      $this->rateReq['DestinationAddress']['Residential'] = ($residentialFlag ? 1 : 0);
   }

   /**
    * Add Item with Calc Rates
    * @param String - Reference Item Code
    * @param Float  - Quantity
    * @param Float  - Length
    * @param Float  - Width
    * @param Float  - Height
    * @param String - Dimensional Units: IN | CM
    * @param Float  - Weigth
    * @param String - Weight Untis: LBS | KGS
    * @param Float  - Declared Value
    * @param String - Packing Method: T | S
    * @access public
    */
   function addItemCalc($refCode, $qty, $weight, $wtUOM, $length, $width ,$height ,$dimUOM, $decVal, $packMethod, $lotSize=1)
   {
      $cnt = (int) count($this->rateReq['ItemList']);
      $this->rateReq['ItemList'][$cnt] = array();
      $this->rateReq['ItemList'][$cnt]['CalcMethod']    = 'C';
      $this->rateReq['ItemList'][$cnt]['RefCode']       = $refCode;
      $this->rateReq['ItemList'][$cnt]['Quantity']      = (float) abs($qty);
      $this->rateReq['ItemList'][$cnt]['LotSize']       = (float) abs($lotSize);
      $this->rateReq['ItemList'][$cnt]['Length']        = (float) abs($length);
      $this->rateReq['ItemList'][$cnt]['Width']         = (float) abs($width);
      $this->rateReq['ItemList'][$cnt]['Height']        = (float) abs($height);
      $this->rateReq['ItemList'][$cnt]['DimUOM']        = (!strcasecmp('CM', $dimUOM) ? 'CM' : 'IN');
      $this->rateReq['ItemList'][$cnt]['Weight']        = (float) abs($weight);
      $this->rateReq['ItemList'][$cnt]['WeightUOM']     = (!strcasecmp('KGS',$wtUOM) ? 'KGS' :  (!strcasecmp('OZ',$wtUOM) ? 'OZ' : 'LBS'));
      $this->rateReq['ItemList'][$cnt]['DeclaredValue'] = (float) abs($decVal);
      $this->rateReq['ItemList'][$cnt]['PackMethod']    = (!strcasecmp('S', $packMethod) ? 'S' : 'T');
      
   }
   
   /**
    * Enable On Demand Services for current Item
    * @param String - carrieer services codes
    * @access public
    */
   function addItemOnDemandServices($services)
   {
      $cnt = (int) count($this->rateReq['ItemList']);
      $current = $cnt - 1;
      $this->rateReq['ItemList'][$current]['OnDemandServices'] = array();
      $odservices = explode(",", $services);
      foreach($odservices AS $key=>$val){
         $this->rateReq['ItemList'][$current]['OnDemandServices'][$key] = $val;      
      }
   }
   

   /**
    * Add Item with Fixed Rates
    * @param String - Reference Item Code
    * @param Float  - Quantity
    * @param String - Fee Type: F | 
    * @param Float  - Fee 1
    * @param Float  - Fee 2
    * @param String - Fixed Fee Code
    * @access public
    */
   function addItemFixed($refCode,$q,$t,$f1,$f2,$c)
   {
      $cnt = (int) count($this->rateReq['ItemList']);
      $this->rateReq['ItemList'][$cnt] = array();
      $this->rateReq['ItemList'][$cnt]['CalcMethod'] = 'F';
      $this->rateReq['ItemList'][$cnt]['RefCode']    = $refCode;
      $this->rateReq['ItemList'][$cnt]['Quantity']   = (float) abs($q);
      $this->rateReq['ItemList'][$cnt]['FeeType']    = (!strcasecmp('F',$t) ? 'F' : 'F');
      $this->rateReq['ItemList'][$cnt]['Fee1']       = (float) abs($f1);
      $this->rateReq['ItemList'][$cnt]['Fee2']       = (float) abs($f2);
      $this->rateReq['ItemList'][$cnt]['FeeCode']    = $c;
   }

   /**
    * Add Carrier
    * @param String - Carrier Code: UPS | USPS | DHL | FDX
    * @param String - Entry Point: E | D
    * @param Bool   - OnDemand Flag
    * @param String - Access Account Key (DHL)
    * @param String - Access Account Postal Code (DHL)
    * @access public
    */
   function addCarrier($carrierCode, $entryPoint, $accessKey=false, $postalCode=false)
   {
  // print("A");
  // print("<BR>addCarrier: " .$entryPoint);
      $code = strtoupper($carrierCode);
      if (!preg_match("/^(UPS|USPS|DHL|FEDEX)\$/",$code)) return false;

      $this->rateReq['CarrierList'][$code] = array();
      $this->rateReq['CarrierList'][$code]['EntryPoint'] = $entryPoint;
      if ($code === 'DHL' && $accessKey && $postalCode) {
         $this->rateReq['CarrierList'][$code]['AccessKey']  = $accessKey;
         $this->rateReq['CarrierList'][$code]['PostalCode'] = strtoupper($postalCode);
      }
	 
   }

   /**
    * Add Service
    * @param String - Carrier Code: UPS | USPS | DHL | FDX
    * @param String - Service Code (see docs for list)
    * @param Bool   - OnDemand Flag
    * @access public
    */
   function addService($carrierCode, $serviceCode, $onDemand=false)
   {
      $code = strtoupper($carrierCode);
      if (!preg_match("/^(UPS|USPS|DHL|FEDEX)\$/",$code)) return false;

      if (isset($this->rateReq['CarrierList'][$code])) {
         if (!isset($this->rateReq['CarrierList'][$code]['ServiceList'])) {
            $this->rateReq['CarrierList'][$code]['ServiceList'] = array();
         }
         $this->rateReq['CarrierList'][$code]['ServiceList'][] = array('Code' => strtoupper($serviceCode),
                                                                       'OnDemand' => $onDemand);
      }
   }

   ### ----------------------------------------------------------------------
   ### Private Support Methods
   ### ----------------------------------------------------------------------

   function _makeGetItemShipRateXS_XML()
   {
      $head = $this->_makeXML_header();
      $body = '<Body><GetItemShipRateXS version="1.0">'.
              '<Currency>'.$this->rateReq['Currency'].'</Currency>'.
              $this->_makeXML_destination().
              $this->_makeXML_origin().
              $this->_makeXML_itemList().
              $this->_makeXML_carrierList().
              '</GetItemShipRateXS></Body>';
      return ('<?xml version="1.0" encoding="utf-8" ?><Envelope>'.$head.$body.'</Envelope>');
   }

   function _makeGetItemShipRateSS_XML()
   {
      $head = $this->_makeXML_header();
      $body = '<Body><GetItemShipRateSS version="1.0">'.
              '<Currency>'.$this->rateReq['Currency'].'</Currency>'.
              $this->_makeXML_destination().
              $this->_makeXML_itemList().
              '</GetItemShipRateSS></Body>';
      return ('<?xml version="1.0" encoding="utf-8" ?><Envelope>'.$head.$body.'</Envelope>');
   }

   function _makeXML_header() {
      $head = '<Header>' . 
         "<AccountId>{$this->accountId}</AccountId>" . 
         (! empty($this->refCode) ? "<RefCode>{$this->refCode}</RefCode>" : '') .
         '</Header>';
      return $head;
   }
   
   function _makeXML_destination()
   {
      $xml = '<DestinationAddress>'.
             '<ResidentialDelivery>'.($this->rateReq['DestinationAddress']['Residential'] ? 'true' : 'false').'</ResidentialDelivery>'.
             '<CountryCode>'.$this->rateReq['DestinationAddress']['CountryCode'].'</CountryCode>'.
             '<StateOrProvinceCode>'.$this->rateReq['DestinationAddress']['StateOrProvinceCode'].'</StateOrProvinceCode>'.
             '<PostalCode>'.$this->rateReq['DestinationAddress']['PostalCode'].'</PostalCode>'.
             '</DestinationAddress>';
      return ($xml);
   }

   function _makeXML_origin()
   {
      $cnt  = count($this->rateReq['OriginAddressList']);
      $xml  = '<OriginAddressList>';
      for ($i = 0; $i < $cnt; $i++) {
          $xml .= '<OriginAddress>'.
                  '<OriginCode>'.$this->rateReq['OriginAddressList'][$i]['OriginCode'].'</OriginCode>'.
                  '<CountryCode>'.$this->rateReq['OriginAddressList'][$i]['CountryCode'].'</CountryCode>'.
                  '<StateOrProvinceCode>'.$this->rateReq['OriginAddressList'][$i]['StateOrProvinceCode'].'</StateOrProvinceCode>'.
                  '<PostalCode>'.$this->rateReq['OriginAddressList'][$i]['PostalCode'].'</PostalCode>'.
                  '</OriginAddress>';

      }
      $xml .= '</OriginAddressList>';
      return ($xml);
   }

   function _makeXML_itemList()
   {
      $cnt  = count($this->rateReq['ItemList']);
      $xml  = '<ItemList>';
      for ($i = 0; $i < $cnt; $i++) {
          $xml .= '<Item>'.
                  '<RefCode>'.$this->rateReq['ItemList'][$i]['RefCode'].'</RefCode>'.
                  '<Quantity>'.$this->rateReq['ItemList'][$i]['Quantity'].'</Quantity>'.
                  '<CalcMethod code="'.$this->rateReq['ItemList'][$i]['CalcMethod'].'">';
             if ($this->rateReq['ItemList'][$i]['CalcMethod'] === 'C') {
                $xml .= '<CarrierCalcProps>'.
                    '<Weight>'.$this->rateReq['ItemList'][$i]['Weight'].'</Weight>'.
                    '<WeightUOM>'.$this->rateReq['ItemList'][$i]['WeightUOM'].'</WeightUOM>'.
                    '<Length>'.$this->rateReq['ItemList'][$i]['Length'].'</Length>'.
                    '<Width>'.$this->rateReq['ItemList'][$i]['Width'].'</Width>'.
                    '<Height>'.$this->rateReq['ItemList'][$i]['Height'].'</Height>'.
                    '<DimUOM>'.$this->rateReq['ItemList'][$i]['DimUOM'].'</DimUOM>'.
                    '<DeclaredValue>'.$this->rateReq['ItemList'][$i]['DeclaredValue'].'</DeclaredValue>'.
                    '<PackMethod>'.$this->rateReq['ItemList'][$i]['PackMethod'].'</PackMethod>'.
                    (isset($this->rateReq['ItemList'][$i]['LotSize']) ? '<LotSize>'.$this->rateReq['ItemList'][$i]['LotSize'].'</LotSize>' : '') ;
                 $cnt_od  = count($this->rateReq['ItemList'][$i]['OnDemandServices']); 
                 if ($cnt_od > 0){
                    $xml .= '<OnDemandServices>';
                    for ($j = 0; $j < $cnt_od; $j++) {
                       $xml .= '<ODService>'.$this->rateReq['ItemList'][$i]['OnDemandServices'][$j].'</ODService>';       
                    } 
                    $xml .= '</OnDemandServices>';    
                 }  
                 $xml .=   '</CarrierCalcProps>';
             } else {
                $xml .= '<CarrierFixedProps>'.
                        '<FeeType>'.$this->rateReq['ItemList'][$i]['FeeType'].'</FeeType>'.
                        '<Fee1>'.$this->rateReq['ItemList'][$i]['Fee1'].'</Fee1>'.
                        '<Fee2>'.$this->rateReq['ItemList'][$i]['Fee2'].'</Fee2>'.
                        '<FeeCode>'.$this->rateReq['ItemList'][$i]['FeeCode'].'</FeeCode>'.
                        '</CarrierFixedProps>';
                  }
          $xml .= '</CalcMethod>';
          $xml .= '</Item>';
                  
      }   
      $xml .= '</ItemList>';
      return ($xml);

   }

   function _makeXML_carrierList()
   {
      $cnt  = count($this->rateReq['CarrierList']);
      $xml  = '<CarrierList>';
      foreach ($this->rateReq['CarrierList'] AS $key => $val) {
         // Make certain that we have both carrier and service codes defined
         if (isset($val['ServiceList']) && sizeof($val['ServiceList']) > 0) {
            $xml .= '<Carrier code="'.$key.'">'.
                    '<EntryPoint>'.$val['EntryPoint'].'</EntryPoint>';
            if ($key === 'DHL') {
               $xml .= '<AccessKey>'.$val['AccessKey'].'</AccessKey>'.
                       '<PostalCode>'.$val['PostalCode'].'</PostalCode>';
            }
            
            $xml .= '<ServiceList>';
            for ($i = 0, $c = count($val['ServiceList']); $i < $c; $i++) {
               if ($val['ServiceList'][$i]['OnDemand']) {
                  $xml .= '<Service code="'.$val['ServiceList'][$i]['Code'].'">'.
                          '<OnDemand>true</OnDemand></Service>';
               } else {
                  $xml .= '<Service code="'.$val['ServiceList'][$i]['Code'].'" />';
               }
            }
            $xml .= '</ServiceList>';
            $xml .= '</Carrier>';
         }
      }
      $xml .= '</CarrierList>';
      return ($xml);
   }
   
   /**
   * Creates an array that mimics thats generated by the API results
   * @param    int      error #
   * @param    string   error message
   * @param    string   Severity (CRITICAL, WARNING, NOTICE)
   * @access   private
   **/
   function _createError($errorCode, $errorMsg, $severity='CRITICAL') {
      $error = array('ErrorList' => array());
      $error['ErrorList'][] = array(
            'Code' => $errorCode,
            'Message' => $errorMsg,
            'Severity' => $severity
            );
      return $error;
   }
   
}

?>
