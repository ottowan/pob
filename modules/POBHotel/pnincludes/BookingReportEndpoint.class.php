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

    $POS = $xml->createElement("POS");
    $POB_HotelBookRQ->appendChild($POS);
    $Source = $xml->createElement("Source");
    $POS->appendChild($Source);
    $RequestorID = $xml->createElement("RequestorID");
    //$RequestorID->setAttribute("Encrypt", "1");
    $RequestorID->setAttribute("Type", "1");
    $RequestorID->setAttribute("ID", "638fdJa7vRmkLs5");
    $Source->appendChild($RequestorID);
    $POS = $xml->createElement("POS");

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
    
  }



  public function sendBookingReportXML(){
    //$url = 'http://pob-ws.heroku.com/api/hotel_book';

    $url = 'http://api.phuketcity.com/api/hotel_book';

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



function extractArrayForDisplay($originalArray){

  $extractArray = array();
  $extractArray["HotelCode"] = $originalArray["HotelRef"]["HotelRef"]["HotelCode"];
  if($originalArray["HotelReservations"]["HotelReservation"]){
    $extractArray["HotelReservations"]["HotelReservations"][0] = $originalArray["HotelReservations"]["HotelReservation"];

    if($extractArray["HotelReservations"]["HotelReservations"][0]["HotelReservation"]["RoomStays"]["RoomStay"]){
      $extractArray["HotelReservations"]["HotelReservations"][0]["HotelReservation"]["RoomStays"]["RoomStays"][0] = $extractArray["HotelReservations"]["HotelReservations"][0]["HotelReservation"]["RoomStays"]["RoomStay"];
      unset($extractArray["HotelReservations"]["HotelReservations"][0]["HotelReservation"]["RoomStays"]["RoomStay"]);
    }
  }else{
    $extractArray["HotelReservations"]["HotelReservations"] = $originalArray["HotelReservations"]["HotelReservations"];

    for($i=0; $i<count($originalArray["HotelReservations"]["HotelReservations"]) ; $i++){
      if($extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStay"]){
        $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][0] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStay"];
        unset($extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStay"]);
      }
    }
  }
  unset($originalArray);
  //$extractArray = $originalArray;

  //print_r($extractArray); exit;
  return $extractArray;

}




public function repackArrayForDisplay($extractArray){


  //print_r($extractArray); exit;
  $repackArray = array();


  for($i=0; $i < count($extractArray["HotelReservations"]["HotelReservations"]);$i++){
    $repackArray["HotelCode"] = $extractArray["HotelCode"];
    //Store new NumberOfUnits array & clean up NumberOfUnits
    $repackArray["HotelReservations"][$i]["BookingID"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["@attributes"]["BookingID"];

    //$repackArray["HotelReservations"][$i] = $extractArray["HotelReservations"]["HotelReservations"][$i];
    $repackArray["HotelReservations"][$i]["RoomStays"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"];

    for($j=0; $j<count($extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"]);$j++){

      //Store new NumberOfUnits array & clean up NumberOfUnits
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["NumberOfUnits"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["RoomTypes"]["RoomType"]["RoomType"]["NumberOfUnits"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["RoomTypes"]);

      //Store new InvCode array & clean up InvCode
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["InvCode"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["Inv"]["Inv"]["InvCode"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["Inv"]);

      //Store new GuestCount array & clean up GuestCount
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["GuestCount"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["GuestCounts"]["GuestCount"]["GuestCount"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["GuestCounts"]);

      //Store new CheckInDate array & clean up CheckInDate
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["CheckInDate"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["TimeSpan"]["TimeSpan"]["Start"];

      //Store new CheckInDate array & clean up CheckInDate
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["CheckOutDate"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["TimeSpan"]["TimeSpan"]["End"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["TimeSpan"]);
/*
      //Store new CardCode array & clean up CardCode
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["PaymentCard"]["CardCode"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["Guarantee"]["GuaranteesAccepted"]["GuaranteeAccepted"]["PaymentCard"]["@attributes"]["CardCode"];

      //Store new CardNumber array & clean up CardNumber
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["PaymentCard"]["CardNumber"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["Guarantee"]["GuaranteesAccepted"]["GuaranteeAccepted"]["PaymentCard"]["@attributes"]["CardNumber"];

      //Store new ExpireDate array & clean up ExpireDate
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["PaymentCard"]["ExpireDate"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["Guarantee"]["GuaranteesAccepted"]["GuaranteeAccepted"]["PaymentCard"]["@attributes"]["ExpireDate"];

      //Store new CardHolderName array & clean up CardHolderName
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["PaymentCard"]["CardHolderName"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["Guarantee"]["GuaranteesAccepted"]["GuaranteeAccepted"]["PaymentCard"]["CardHolderName"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["Guarantee"]);
*/

      //Store new ChainCode array & clean up ChainCode
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["BasicPropertyInfo"]["ChainCode"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["BasicPropertyInfo"]["BasicPropertyInfo"]["ChainCode"];

      //Store new HotelCode array & clean up HotelCode
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["BasicPropertyInfo"]["HotelCode"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["BasicPropertyInfo"]["BasicPropertyInfo"]["HotelCode"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["BasicPropertyInfo"]);


      //Store new HotelCode array & clean up HotelCode
      $repackArray["HotelReservations"][$i]["RoomStays"][$j]["Comment"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["RoomStays"]["RoomStays"][$j]["Comments"]["Comment"]["Text"];
      unset($repackArray["HotelReservations"][$i]["RoomStays"][$j]["Comments"]);
    }
        
      //////////////////////////////////////////////
      //RestGuest
      //////////////////////////////////////////////
      $repackArray["HotelReservations"][$i]["Customer"] = $extractArray["HotelReservations"]["HotelReservations"][$i]["HotelReservation"]["ResGuests"]["ResGuest"]["Profiles"]["ProfileInfo"]["Profile"]["Customer"];
      //unset($repackArray["HotelReservations"][$i]["ResGuests"]);

  }

  unset($extractArray);

  //print_r($repackArray); exit;
  return $repackArray;

}

  public function requestSampleBookingReportXML(){
    //$data = file_get_contents('http://localhost/php/pob/hotel_book_rs.xml');
    //$data = file_get_contents('http://localhost/php/pob/hotel_book_rs2.xml');
    //$data = file_get_contents('http://localhost/php/pob/hotel_book_rs3.xml');
    $data = file_get_contents('http://localhost/php/pob/hotel_book_rs4.xml');

    return $data;
  }

  function mileToKilometre($mile){
    return ($mile * 1.609344);
  }

}


?>