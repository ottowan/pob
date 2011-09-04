<?php


function _preRender(&$render){
  $lang    = FormUtil::getPassedValue ('lang', false , 'GET');

  if ($lang){
    $render->assign('lang', $lang);
  }else{
    $render->assign('lang', pnUserGetLang());
  }

}

function POBRoomSearch_search_viewold(){

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
    //Send param to RoomSearch service 
    Loader::loadClass('RoomSearchEndpoint',"modules/POBRoomSearch/pnincludes");
    $roomSearch = new RoomSearchEndpoint();
    $roomSearch->setRoomSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate);

    //XML Response
    $response = $roomSearch->getRoomSearchXML();
    //var_dump($response); exit;

    //Convert xml response to array
    Loader::loadClass('POBReader',"modules/POBRoomSearch/pnincludes");
    $reader = new POBReader();
    $arrayResponse = $reader->xmlToArray($response);

    $arrayResponse["startDate"] = $startDate;
    $arrayResponse["endDate"] = $endDate;
    //var_dump($arrayResponse); exit;
    $repackArray = array();
    $repackArray = $roomSearch->repackArrayForDisplay($arrayResponse);

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

function POBRoomSearch_search_view(){

  $render = pnRender::getInstance('POBRoomSearch');

  //Load language
  $lang = pnUserGetLang();
  if (file_exists('modules/POBRoomSearch/pnlang/' . $lang . '/user.php')){
    Loader::loadFile('user.php', 'modules/POBRoomSearch/pnlang/' . $lang );
  }else if (file_exists('modules/POBRoomSearch/pnlang/eng/user.php')){
    Loader::loadFile('user.php', 'modules/POBRoomSearch/pnlang/eng' );
  }
  
  $startDay = FormUtil::getPassedValue ('startDay', FALSE, 'POST');
  $startMonth = FormUtil::getPassedValue ('startMonth', FALSE, 'POST');
  $startYear = FormUtil::getPassedValue ('startYear', FALSE, 'POST');

  $endDay = FormUtil::getPassedValue ('endDay', FALSE, 'POST');
  $endMonth = FormUtil::getPassedValue ('endMonth', FALSE, 'POST');
  $endYear = FormUtil::getPassedValue ('endYear', FALSE, 'POST');

  //Change thai yeasr to US year
  if(($startYear-543) == date("Y")){
    $startYear = date("Y");
  }else if(($startYear-543) == date("Y")+1){
    $startYear = date("Y")+1;
  }
  if(($endYear-543) == date("Y")){
    $endYear = date("Y");
  }else if(($endYear-543) == date("Y")+1){
    $endYear = date("Y")+1;
  }

  $startDate = $startYear."-".$startMonth."-".$startDay;
  $endDate   = $endYear."-".$endMonth."-".$endDay;

  $hotelArray = pnModAPIFunc('POBHotel', 'user', 'getHotelCode');
  //var_dump($latlonArray); exit;
  $hotelCode  = $hotelArray["hotelcode"];



  if($hotelCode){
    //$distance  = "0";
    //Send param to RoomSearch service 
    Loader::loadClass('RoomSearchEndpoint',"modules/POBRoomSearch/pnincludes");
    $roomSearch = new RoomSearchEndpoint();
    $roomSearch->setRoomSearchXML( $hotelCode, $startDate, $endDate );

    //XML Response
    $response = $roomSearch->getRoomSearchXML();
    //echo ($response); exit;

    $xmlObject = simplexml_load_string($response);
    //print_r($xmlObject); exit;

    $repackObjectArray = $roomSearch->repackObjectArrayForDisplay($xmlObject);
  
    //echo count($repackObjectArray["Properties"]); exit;
    if(count($repackObjectArray["Properties"]) == 1 && $repackObjectArray){
      $view = $repackObjectArray["Properties"][0];
    }

    //Get Domain Name
    $args = array("hotelcode"=>$view["HotelCode"]);
    $api = pnModAPIFunc('POBMember', 'user', 'getDomainName', $args);
    //print_r($api); exit;

    if(isset($api["domainname"])){
      $view["URL"] = $api["domainname"].".phuketcity.com";
    }else{
      $view["URL"] = "false";
    }
    $view["startDate"] = $startDate;
    $view["endDate"] = $endDate;

    $issetArray  = true;
  }else{
    $issetArray = false;
  }
    

    _preRender($render);

  if($issetArray == true){
    $render->assign("view", $view );
    return $render->fetch('user_view_room.htm');
  }else{
    $render->assign("view", null );
    return $render->fetch('user_view_room.htm');
  }
}


?>