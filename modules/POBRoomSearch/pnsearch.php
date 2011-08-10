<?php


function _preRender(&$render){
  $lang    = FormUtil::getPassedValue ('lang', false , 'GET');

  if ($lang){
    $render->assign('lang', $lang);
  }else{
    $render->assign('lang', pnUserGetLang());
  }

}

function POBRoomSearch_search_view(){

  $render = pnRender::getInstance('POBRoomSearch');

  
  $startDay = FormUtil::getPassedValue ('startDay', FALSE, 'POST');
  $startMonth = FormUtil::getPassedValue ('startMonth', FALSE, 'POST');
  $startYear = FormUtil::getPassedValue ('startYear', FALSE, 'POST');

  $endDay = FormUtil::getPassedValue ('endDay', FALSE, 'POST');
  $endMonth = FormUtil::getPassedValue ('endMonth', FALSE, 'POST');
  $endYear = FormUtil::getPassedValue ('endYear', FALSE, 'POST');



  //Load language
  $lang = pnUserGetLang();
  if (file_exists('modules/POBRoomSearch/pnlang/' . $lang . '/user.php')){
    Loader::loadFile('user.php', 'modules/POBRoomSearch/pnlang/' . $lang );
  }else if (file_exists('modules/POBRoomSearch/pnlang/eng/user.php')){
    Loader::loadFile('user.php', 'modules/POBRoomSearch/pnlang/eng' );
  }


  $startDate = $startYear."-".$startMonth."-".$startDay;
  $endDate   = $endYear."-".$endMonth."-".$endDay;

  $latlonArray = pnModAPIFunc('POBHotel', 'user', 'getLatLon');
  //var_dump($latlonArray); exit;
  $latitude  = $latlonArray["latitude"];
  $longitude = $latlonArray["longitude"];


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
    

    _preRender($render);

  if($issetArray == true){
    $render->assign("view", $repackArray );
    return $render->fetch('user_view_room.htm');
  }else{
    $render->assign("view", null );
    return $render->fetch('user_view_room.htm');
  }
}

?>