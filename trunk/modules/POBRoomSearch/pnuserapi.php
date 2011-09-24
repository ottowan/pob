<?php

/////////////////////////////
//    return availability
//    $args = array("hotelcode"=>"POBHT000005", "startdate"=> 2011-09-24, "enddate"=> 2011-09-25);
//    $availability = pnModAPIFunc('POBRoomSearch', 'user', 'getAvailability', $args);
//
////////////////////////////
function POBRoomSearch_userapi_getAvailability($args) {

  $hotelCode = $args['hotelcode'];
  $startDate = $args['startdate'];
  $endDate   = $args['enddate'];

  if($hotelCode && $startDate && $endDate){

    //Send param to RoomSearch service 
    Loader::loadClass('RoomSearchEndpoint',"modules/POBRoomSearch/pnincludes");
    $roomSearch = new RoomSearchEndpoint();
    $roomSearch->setRoomSearchXML( $hotelCode, $startDate, $endDate );

    //XML Response
    $response = $roomSearch->getRoomSearchXML();

    $xmlObject = simplexml_load_string($response);

    $repackObjectArray = $roomSearch->repackObjectArrayForDisplay($xmlObject);
    
    if(count($repackObjectArray["Properties"]) == 1 && $repackObjectArray){
      $view = $repackObjectArray["Properties"][0];
    }

    $view["startDate"] = $startDate;
    $view["endDate"] = $endDate;

    $issetArray  = true;
  }else{
    $issetArray = false;
  }

  if($issetArray == true){
    return $view;
  }else{
    return null;
  }
}



?>