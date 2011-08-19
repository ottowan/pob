<?php
  /**
  * 
  * 
  * 
  */
Class BookingReportEndpoint {
  private $hotelCode  = NULL;
  private $startDate = NULL;
  private $endDate   = NULL;
  
  
  function __construct(){
  }

  public function setBookingReportXML($hotelCode = NULL, $startDate = NULL, $endDate   = NULL){
    $this->hotelCode  = $hotelCode;
    $this->startDate = $startDate;
    $this->endDate   = $endDate;
  }

  public function genBookingReportXML(){

    $xml = new DOMDocument();
    $xml->preserveWhiteSpace = true;
    $xml->formatOutput = true;

    //OTA_BookingReportRQ
    $POB_HotelBookRQ = $xml->createElement("POB_HotelBookRQ");

    // Set the attributes.
    $POB_HotelBookRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $POB_HotelBookRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $POB_HotelBookRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd"); 
    $POB_HotelBookRQ->setAttribute("Version", "1.003");

    if($this->hotelCode && $this->startDate && $this->endDate){
      $HotelRef = $xml->createElement("HotelRef");
      $HotelRef->setAttribute("ChainCode", "MC");
      $HotelRef->setAttribute("HotelCode", $this->hotelCode);

      $TimeSpan = $xml->createElement("TimeSpan");
      $TimeSpan->setAttribute("Start", $this->startDate);
      $TimeSpan->setAttribute("End", $this->endDate);

      $POB_HotelBookRQ->appendChild($HotelRef);
      $POB_HotelBookRQ->appendChild($TimeSpan);
    }

    $xml->appendChild($POB_HotelBookRQ);
    
    return $xml->saveXML();
    //echo $xml->saveXML(); exit;
  }

  public function sampleBookingReportXML(){
    $temp = '<POB_HotelBookRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd" Version="1.003">
<HotelRef ChainCode="MC" HotelCode="SONGRIT"/>
<TimeSpan Start="2011-08-18" End="2011-08-19"/>
</POB_HotelBookRQ>';
    return $temp;
  }

  public function sendBookingReportXML(){
    $url = 'http://pob-ws.heroku.com/api/hotel_book';
    $data = $this->genBookingReportXML();
    //$data = $this->sampleBookingReportXML();

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



  public function getBookingReportXML(){

    //Load reader class
    //Loader::loadClass('POBReader',"modules/POBBookingReport/pnincludes");
    //$reader = new POBReader();
    
    //Get data from search
    $response = $this->sendBookingReportXML();
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