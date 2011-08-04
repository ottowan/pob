<?php
function POBPortal_search_searchResult(){

  $render = pnRender::getInstance('POBPortal');

  $form = FormUtil::getPassedValue ('form', FALSE, 'POST');
  
  $startDay = FormUtil::getPassedValue ('startDay', FALSE, 'POST');
  $startMonth = FormUtil::getPassedValue ('startMonth', FALSE, 'POST');
  $startYear = FormUtil::getPassedValue ('startYear', FALSE, 'POST');

  $endDay = FormUtil::getPassedValue ('endDay', FALSE, 'POST');
  $endMonth = FormUtil::getPassedValue ('endMonth', FALSE, 'POST');
  $endYear = FormUtil::getPassedValue ('endYear', FALSE, 'POST');
  
  if(!$form['search']){
    $form['search'] = "phuket";
    $form['page'] = 1;
    $location  = "phuket";
    $distance  = "10";
    $latitude  = "7.88806";
    $longitude = "98.3975";
    $startDate = $startYear."-".$startMonth."-".$startDay;
    $endDate   = $endYear."-".$endMonth."-".$endDay;
  }

  //Send param to HotelSearch service 
  Loader::loadClass('HotelSearchEndpoint',"modules/POBPortal/pnincludes");
  $hotelSearch = new HotelSearchEndpoint();
  $hotelSearch->setHotelSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate);

  //XML Response
  $response = $hotelSearch->getHotelSearchXML();
  //print($response); exit;

  //Convert xml response to array
  Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
  $reader = new POBReader();
  $arrayResponse = $reader->xmlToArray($response);
  $arrayResponse["startDate"] = $startDate;
  $arrayResponse["endDate"] = $endDate;

  $repackArray = array();
  $repackArray = $hotelSearch->repackArrayForDisplay($arrayResponse);
  //echo count($repackArray); exit;
  //var_dump($repackArray[3]["ThumbItem"]["URL"]); exit;


  if($repackArray){
    $render->assign("objectArray", $repackArray );
    return $render->fetch('user_list_hotel.htm');
  }else{
    $render->assign("objectArray", null );
    return $render->fetch('user_list_hotel.htm');
  }

}



function POBPortal_search_view(){

  $render = pnRender::getInstance('POBPortal');

  $startDate = FormUtil::getPassedValue ('startDate', FALSE, 'REQUEST');
  $endDate = FormUtil::getPassedValue ('endDate', FALSE, 'REQUEST');
  $latitude = FormUtil::getPassedValue ('lat', FALSE, 'REQUEST');
  $longitude = FormUtil::getPassedValue ('lon', FALSE, 'REQUEST');

  if($latitude && $longitude){
    $distance  = "0.001";
    //$distance  = "0";
    //Send param to HotelSearch service 
    Loader::loadClass('HotelSearchEndpoint',"modules/POBPortal/pnincludes");
    $hotelSearch = new HotelSearchEndpoint();
    $hotelSearch->setHotelSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate);

    //XML Response
    $response = $hotelSearch->getHotelSearchXML();
    //print($response); exit;

    //Convert xml response to array
    Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
    $reader = new POBReader();
    $arrayResponse = $reader->xmlToArray($response);
    //var_dump($arrayResponse["Properties"]["Property"]["RelativePosition"]); exit;
    $arrayResponse["startDate"] = $startDate;
    $arrayResponse["endDate"] = $endDate;

    $repackArray = array();
    $repackArray = $hotelSearch->repackArrayForDisplay($arrayResponse);
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
    return $render->fetch('user_view_hotel.htm');
  }else{
    $render->assign("view", null );
    return $render->fetch('user_view_hotel.htm');
  }
}

?>