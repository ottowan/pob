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
    
  }



  public function sendBookingReportXML(){
    $url = 'http://pob-ws.heroku.com/api/hotel_book';
    //$data = $this->genBookingReportXML();
    $data = $this->sampleBookingReportXML();

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
  $extractArray[HotelCode] = $originalArray[HotelRef][HotelRef][HotelCode];
  if($originalArray[HotelReservations][HotelReservation]){
    $extractArray[HotelReservations][HotelReservations][0] = $originalArray[HotelReservations][HotelReservation];

    if($extractArray[HotelReservations][HotelReservations][0][RoomStays][RoomStay]){
      $extractArray[HotelReservations][HotelReservations][0][RoomStays][RoomStays][0] = $extractArray[HotelReservations][HotelReservations][0][RoomStays][RoomStay];
      unset($extractArray[HotelReservations][HotelReservations][0][RoomStays][RoomStay]);
    }
  }else{
    $extractArray[HotelReservations][HotelReservations] = $originalArray[HotelReservations][HotelReservations];

    for($i=0; $i<count($extractArray[HotelReservations][HotelReservations]) ; $i++){
      if($extractArray[HotelReservations][HotelReservations][$i][RoomStays][RoomStay]){
        $extractArray[HotelReservations][HotelReservations][$i][RoomStays][RoomStays][0] = $extractArray[HotelReservations][HotelReservations][$i][RoomStays][RoomStay];
        unset($extractArray[HotelReservations][HotelReservations][$i][RoomStays][RoomStay]);
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


  for($i=0; $i < count($extractArray[HotelReservations][HotelReservations]);$i++){
    $repackArray[HotelCode] = $extractArray[HotelCode];
    $repackArray[HotelReservations][$i] = $extractArray[HotelReservations][HotelReservations][$i];
    $repackArray[HotelReservations][$i][RoomStays] = $extractArray[HotelReservations][HotelReservations][$i][RoomStays][RoomStays];
//    $repackArray[HotelReservations][$i][RoomStays][NumberOfUnits] = $extractArray[HotelReservations][HotelReservations][$i][RoomStays][RoomStays][RoomTypes][RoomType][RoomType];
    unset($repackArray[HotelReservations][$i][RoomStays][RoomStays]);
  }


  unset($extractArray);

  print_r($repackArray); exit;
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