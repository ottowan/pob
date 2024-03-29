<?php
  /**
  * 
  * 
  * 
  */
Class HotelSearchEndpoint {
  private $location  = NULL;
  private $distance  = 0;
  private $latitude  = NULL;
  private $longitude = NULL;
  private $startDate = NULL;
  private $endDate   = NULL;
  
  
  function __construct(){
  }

  public function setHotelSearchXML( $location  = NULL,  $distance  = 0, $latitude  = NULL, $longitude = NULL, $startDate = NULL, $endDate   = NULL){
    $this->location  = $location;
    $this->distance  = $distance;
    $this->latitude  = $latitude;
    $this->longitude = $longitude;
    $this->startDate = $startDate;
    $this->endDate   = $endDate;
  }

  public function genHotelSearchXML(){

    $xml = new DOMDocument();
    $xml->preserveWhiteSpace = true;
    $xml->formatOutput = true;

      //OTA_HotelSearchRQ
    $OTA_HotelSearchRQ = $xml->createElement("OTA_HotelSearchRQ");

    // Set the attributes.
    $OTA_HotelSearchRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelSearchRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelSearchRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd");
    $OTA_HotelSearchRQ->setAttribute("EchoToken", "HL");
    $OTA_HotelSearchRQ->setAttribute("Target", "Production");
    $OTA_HotelSearchRQ->setAttribute("Version", "2.004");
    $OTA_HotelSearchRQ->setAttribute("PrimaryLangID", "EN-US");
    $OTA_HotelSearchRQ->setAttribute("ResponseType", "PropertyList");

    $POS = $xml->createElement("POS");
    $OTA_HotelSearchRQ->appendChild($POS);

    $Source = $xml->createElement("Source");
    $POS->appendChild($Source);

    $RequestorID = $xml->createElement("RequestorID");
    $RequestorID->setAttribute("ID", "638fdJa7vRmkLs5");
    $RequestorID->setAttribute("ID_Context", "RZ");

    $Criteria = $xml->createElement("Criteria");
    $Criterion = $xml->createElement("Criterion");
    $Position = $xml->createElement("Position");
    $Position->setAttribute("Latitude", $this->latitude);
    $Criterion->appendChild($Position);

    $Position = $xml->createElement("Position");
    $Position->setAttribute("Longitude", $this->longitude);
    $Criterion->appendChild($Position);

    $Radius = $xml->createElement("Radius");
    $Radius->setAttribute("DistanceMeasure", "MILES");
    $Radius->setAttribute("Distance", $this->distance);
    $Criterion->appendChild($Radius);

    if($this->startDate && $this->endDate){
      $StayDateRange = $xml->createElement("StayDateRange");
      $StayDateRange->setAttribute("Start", $this->startDate);
      $StayDateRange->setAttribute("End", $this->endDate);
      $Criterion->appendChild($StayDateRange);
    }

    $Source->appendChild($RequestorID);



    $Criteria->appendChild($Criterion);
    $OTA_HotelSearchRQ->appendChild($Criteria);
    $xml->appendChild($OTA_HotelSearchRQ);
    
    

    return $xml->saveXML();;
    //echo $out; exit;
  }

  public function hotelSearchTemp(){
      $temp = '<?xml version="1.0" encoding="UTF-8"?>
      <OTA_HotelSearchRQ  xmlns="http://www.opentravel.org/OTA/2003/05" 
                          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                          xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" 
                          EchoToken="HL" 
                          Target="Production" 
                          Version="1.003" 
                          PrimaryLangID="EN-US" 
                          ResponseType="PropertyList"
       >
        <POS>
          <Source AirlineVendorID="FG" PseudoCityCode="MIA" ISOCountry="US" ISOCurrency="USD" AgentSine="A4444BM" AgentDutyCode="FR">
          </Source>
          <Source>
            <RequestorID Type="5" ID="12345675" ID_Context="IATA"/>
              <!--5 means travel agency-->
          </Source>
        </POS>
        <Criteria>
          <Criterion>
            <Position Latitude="7.88806" />
            <Position Longitude="98.3975" />
            <Radius DistanceMeasure="MILES" Distance="10000" />
            <StayDateRange Start="2004-08-02" End="2004-08-04" />
            <RefPoint>EIFFEL TOWER</RefPoint>
            <CodeRef LocationCode="23" CodeContext="OTA-REF code list"/>
              <!--23 means monument location code-->
            <HotelRef HotelCityCode="PAR"/>
            <Radius Distance="2" DistanceMeasure="MILES"/>
            <RoomAmenity RoomAmenity="74"/>
              <!-- 74 means non smoking-->
            <RoomAmenity RoomAmenity="158"/>
              <!--158 means CNN is in the room -->
            <RoomAmenity RoomAmenity="123"/>
              <!--123 means wireless internet access-->
            </Criterion>
        </Criteria>
      </OTA_HotelSearchRQ>';
  }

  public function sendHotelSearchXML(){
    $url = 'http://pob-ws.heroku.com/api/hotel_search';
    $data = $this->genHotelSearchXML();
    //$data = $data->saveXML();

    //$data = $data->saveXML();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    //echo($response); exit;
    return $response;
  }



  public function getHotelSearchXML(){

    //Load reader class
    //Loader::loadClass('POBReader',"modules/POBRoomSearch/pnincludes");
    //$reader = new POBReader();
    
    //Get data from search
    $response = $this->sendHotelSearchXML();
    //print $response; exit;
    //Convert xml to array
    //$arrayResponse = $reader->xmlToArray($response);
    //var_dump($arrayResponse); exit;
    return $response;
  }



function repackArrayForDisplay($originalArray){
  $distanceValidate = floor($originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"]);
  $repackArray = array();
  if($distanceValidate == 0 && $originalArray["Properties"]["Property"]){
    if(!is_null($originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"]) || !is_null($originalArray["Properties"]["Property"]["Availabilities"]["Availability"]["Availability"])){
      //var_dump($originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"]); exit;
      //////////////////////////////////////////
      //Display one item (page view)
      //////////////////////////////////////////
      //Repack Hotel information
      $repackArray["HotelCode"] = $originalArray["Properties"]["Property"]["@attributes"]["HotelCode"];
      $repackArray["HotelName"] = $originalArray["Properties"]["Property"]["@attributes"]["HotelName"];
      $repackArray["Description"]   = $originalArray["Properties"]["Property"]["@attributes"]["Description"];

      //Repack Relative position
      $repackArray["Direction"]        = $originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Direction"];
      $repackArray["DistanceUnitName"] = $originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["DistanceUnitName"];
      $repackArray["Distance"]         = number_format($this->mileToKilometre($originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"]), 2);
      $repackArray["Latitude"]         = $originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Latitude"];
      $repackArray["Longitude"]        = $originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Longitude"];

      //Repack ContactInfo
      $repackArray["AddressLine"] = $originalArray["Properties"]["Property"]["ContactInfo"]["ContactInfo"]["AddressLine"];
      $repackArray["CityName"]    = $originalArray["Properties"]["Property"]["ContactInfo"]["ContactInfo"]["CityName"];
      $repackArray["CountryName"] = $originalArray["Properties"]["Property"]["ContactInfo"]["ContactInfo"]["CountryName"];
      $repackArray["PhoneNumber"] = $originalArray["Properties"]["Property"]["ContactInfo"]["ContactInfo"]["PhoneNumber"];
      $repackArray["PostalCode"]  = $originalArray["Properties"]["Property"]["ContactInfo"]["ContactInfo"]["PostalCode"];
      $repackArray["StateProv"]   = $originalArray["Properties"]["Property"]["ContactInfo"]["ContactInfo"]["StateProv"];

      if($originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"]){
          for($j=0; $j<count($originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"]); $j++){
            $repackArray["Availabilities"][$j]["Date"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"][$j]["Availability"]["Date"];
            $repackArray["Availabilities"][$j]["InvCode"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"][$j]["Availability"]["InvCode"];
            $repackArray["Availabilities"][$j]["Limit"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"][$j]["Availability"]["Limit"];
            $repackArray["Availabilities"][$j]["Rate"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"][$j]["Availability"]["Rate"];
            $repackArray["Availabilities"][$j]["RatePlanCode"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availabilities"][$j]["Availability"]["RatePlanCode"];
          }
      //}else if($originalArray["Properties"]["Property"]["Availabilities"]["Availability"]){
      }else{
        //var_dump($originalArray["Properties"]["Property"]["Availabilities"]); exit;
        $repackArray["Availabilities"][0]["Date"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availability"]["Availability"]["Date"];
        $repackArray["Availabilities"][0]["InvCode"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availability"]["Availability"]["InvCode"];
        $repackArray["Availabilities"][0]["Limit"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availability"]["Availability"]["Limit"];
        $repackArray["Availabilities"][0]["Rate"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availability"]["Availability"]["Rate"];
        $repackArray["Availabilities"][0]["RatePlanCode"] = $originalArray["Properties"]["Property"]["Availabilities"]["Availability"]["Availability"]["RatePlanCode"];
      }

      if($originalArray["Properties"]["Property"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"]){
        $repackArray[$i]["ImageItems"][0]["url"] = $originalArray["Properties"]["Property"][4]["MultimediaDescriptions"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"][0]["ImageFormat"][0]["URL"];
      } //End check image

    $ImageItems = array();
    $ImageItems = $originalArray["Properties"]["Property"]["MultimediaDescriptions"]["MultimediaDescriptions"][5]["ImageItems"]["ImageItems"];
    for($k=0; $k<count($ImageItems); $k++){
      $repackArray["ImageItems"][$k]["category"] = $ImageItems[$k]["@attributes"]["Category"];
      $ImageItem = $ImageItems[$k]["ImageFormat"];
      for($l=0; $l<count($ImageItem); $l++){

        if($repackArray["ImageItems"][$k]["category"] == 6){
          $repackArray["ImageItems"][$k]["ImageItem"][$l]["URL"] = $ImageItem[$l]["URL"];
        }

        if($repackArray["ImageItems"][$k]["category"] == 1){
          $repackArray["ImageItems"][$k]["ThumbItem"][$l]["URL"] = $ImageItem[$l]["URL"];
        }

    }//End loop ImageItem

      //var_dump($repackArray["ImageItems"][$k]["ImageItem"]); exit;

    }//End loop ImageItems

    }// End check price
  }

  //var_dump($originalArray["Properties"]["Properties"][4]["MultimediaDescriptions"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"][0]["ImageFormat"][0]["URL"]); exit;


  return $repackArray;

}


function mileToKilometre($mile){
  return ($mile * 1.609344);
}

}


?>