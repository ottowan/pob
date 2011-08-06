<?php
/*
function POBRoomSearch_search_searchResult(){

  $render = pnRender::getInstance('POBRoomSearch');

  $form = FormUtil::getPassedValue ('form', FALSE, 'POST');
  
  $startDay = FormUtil::getPassedValue ('startDay', FALSE, 'POST');
  $startMonth = FormUtil::getPassedValue ('startMonth', FALSE, 'POST');
  $startYear = FormUtil::getPassedValue ('startYear', FALSE, 'POST');

  $endDay = FormUtil::getPassedValue ('endDay', FALSE, 'POST');
  $endMonth = FormUtil::getPassedValue ('endMonth', FALSE, 'POST');
  $endYear = FormUtil::getPassedValue ('endYear', FALSE, 'POST');
  

  $location  = "phuket";
  $distance  = "0.001";
  $latlonArray = pnModAPIFunc('POBHotel', 'user', 'getLatLon');
  $latitude  = $latlonArray["latitude"];
  $longitude = $latlonArray["longitude"];
  $startDate = $startYear."-".$startMonth."-".$startDay;
  $endDate   = $endYear."-".$endMonth."-".$endDay;

  //Send param to HotelSearch service 
  Loader::loadClass('HotelSearchEndpoint',"modules/POBRoomSearch/pnincludes");
  $hotelSearch = new HotelSearchEndpoint();
  $hotelSearch->setHotelSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate);

  //XML Response
  $response = $hotelSearch->getHotelSearchXML();
  //print($response); exit;

  //Convert xml response to array
  Loader::loadClass('POBReader',"modules/POBRoomSearch/pnincludes");
  $reader = new POBReader();
  $arrayResponse = $reader->xmlToArray($response);
  $arrayResponse["startDate"] = $startDate;
  $arrayResponse["endDate"] = $endDate;

  $repackArray = array();
  $repackArray = repackArrayForDisplay($arrayResponse);
  //echo count($repackArray); exit;
  //var_dump($repackArray[3]["ThumbItem"]["URL"]); exit;


  if($repackArray){
    $render->assign("objectArray", $repackArray );
    return $render->fetch('user_list_room.htm');
  }else{
    $render->assign("objectArray", null );
    return $render->fetch('user_list_room.htm');
  }

}
*/


function POBRoomSearch_search_view(){

  $render = pnRender::getInstance('POBRoomSearch');

  
  $startDay = FormUtil::getPassedValue ('startDay', FALSE, 'POST');
  $startMonth = FormUtil::getPassedValue ('startMonth', FALSE, 'POST');
  $startYear = FormUtil::getPassedValue ('startYear', FALSE, 'POST');

  $endDay = FormUtil::getPassedValue ('endDay', FALSE, 'POST');
  $endMonth = FormUtil::getPassedValue ('endMonth', FALSE, 'POST');
  $endYear = FormUtil::getPassedValue ('endYear', FALSE, 'POST');


  $startDate = $startYear."-".$startMonth."-".$startDay;
  $endDate   = $endYear."-".$endMonth."-".$endDay;

  $latlonArray = pnModAPIFunc('POBHotel', 'user', 'getLatLon');
  //var_dump($latlonArray); exit;
  $latitude  = $latlonArray["latitude"];
  $longitude = $latlonArray["longitude"];

  //$latitude  = "7.771828058680014";
  //$longitude = "98.3205502599717";

  //var_dump($longitude); exit;

  if($latitude && $longitude){
    $distance  = "0.001";


    //$distance  = "0";
    //Send param to HotelSearch service 
    Loader::loadClass('HotelSearchEndpoint',"modules/POBRoomSearch/pnincludes");
    $hotelSearch = new HotelSearchEndpoint();
    $hotelSearch->setHotelSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate);

    //XML Response
    $response = $hotelSearch->getHotelSearchXML();
    //var_dump($response); exit;

    //Convert xml response to array
    Loader::loadClass('POBReader',"modules/POBRoomSearch/pnincludes");
    $reader = new POBReader();
    $arrayResponse = $reader->xmlToArray($response);

    $arrayResponse["startDate"] = $startDate;
    $arrayResponse["endDate"] = $endDate;
    //var_dump($arrayResponse); exit;
    $repackArray = array();
    $repackArray = $hotelSearch->repackArrayForDisplay($arrayResponse);

    //echo ((int)$repackArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"]); exit;
    
    if($repackArray){
      $issetArray  = true;
    }else{
      $issetArray  = false;
    }

  }else{
    $issetArray = false;
  }
    
  if($issetArray == true){
    $render->assign("view", $repackArray );
    return $render->fetch('user_view_room.htm');
  }else{
    $render->assign("view", null );
    return $render->fetch('user_view_room.htm');
  }
}

?>