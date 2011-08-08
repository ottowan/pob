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
  

//echo date("Y")+1; exit;

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

  //var_dump($endYear); exit;

  if(!$form['search']){
    $form['search'] = "phuket";
    $form['page'] = 1;
    $location  = "phuket";
    $distance  = "10";
    $latitude  = "7.88806";
    $longitude = "98.3975";
    $startDate = $startYear."-".$startMonth."-".$startDay;
    $endDate   = $endYear."-".$endMonth."-".$endDay;
  }else{
    $referencePoint = referencePoint($form['search']);
    $location  = $form['search'];
    $distance  = "2";
    $latitude  = $referencePoint["latitude"];
    $longitude = $referencePoint["longitude"];
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
  //print_r($arrayResponse["Properties"]); exit;
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




  function referencePoint($key){
    $reference = array(
                        "patong"=>array(
                                        "latitude"=> "7.894669",
                                        "longitude"=> "98.295708"
                                 ),
                        "ป่าตอง"=>array(
                                        "latitude"=> "7.894669",
                                        "longitude"=> "98.295708"
                                 ),
                        "kata"=>array(
                                        "latitude"=> "7.821123",
                                        "longitude"=> "98.299356"
                                 ),
                        "กะตะ"=>array(
                                        "latitude"=> "7.821123",
                                        "longitude"=> "98.299356"
                                 ),
                        "karon"=>array(
                                        "latitude"=> "7.850118",
                                        "longitude"=> "98.298111"
                                 ),
                        "กะรน"=>array(
                                        "latitude"=> "7.850118",
                                        "longitude"=> "98.298111"
                                 ),
                        "rawai"=>array(
                                        "latitude"=> "7.77971",
                                        "longitude"=> "98.325577"
                                 ),
                        "ราไวย์"=>array(
                                        "latitude"=> "7.77971",
                                        "longitude"=> "98.325577"
                                 ),
                        "kathu"=>array(
                                        "latitude"=> "7.911332",
                                        "longitude"=> "98.333473"
                                 ),
                        "กะทู้"=>array(
                                        "latitude"=> "7.911332",
                                        "longitude"=> "98.333473"
                                 ),
                        "phukettown"=>array(
                                        "latitude"=> "7.890248",
                                        "longitude"=> "98.383255"
                                 ),
                        "ภูเก็ตทาวน์"=>array(
                                        "latitude"=> "7.890248",
                                        "longitude"=> "98.383255"
                                 ),
                        "kamala"=>array(
                                        "latitude"=> "7.948397",
                                        "longitude"=> "98.277855"
                                 ),
                        "กมลา"=>array(
                                        "latitude"=> "7.948397",
                                        "longitude"=> "98.277855"
                                 ),
                        "MaiKhao"=>array(
                                        "latitude"=> "8.134008",
                                        "longitude"=> "98.305664"
                                 ),
                        "ไม้ขาว"=>array(
                                        "latitude"=> "8.134008",
                                        "longitude"=> "98.305664"
                                 ),
                        "Chalong"=>array(
                                        "latitude"=> "7.847737",
                                        "longitude"=> "98.33828"
                                 ),
                        "ฉลอง"=>array(
                                        "latitude"=> "7.847737",
                                        "longitude"=> "98.33828"
                                 ),
                        "panwa"=>array(
                                        "latitude"=> "7.806157",
                                        "longitude"=> "98.406601"
                                 ),
                        "พันวา"=>array(
                                        "latitude"=> "7.806157",
                                        "longitude"=> "98.406601"
                                 ),
                        "layan"=>array(
                                        "latitude"=> "8.029145",
                                        "longitude"=> "98.291416"
                                 ),
                        "ลายัน"=>array(
                                        "latitude"=> "8.029145",
                                        "longitude"=> "98.291416"
                                 ),
                        "thalang"=>array(
                                        "latitude"=> "8.038153",
                                        "longitude"=> "98.33313"
                                 ),
                        "ถลาง"=>array(
                                        "latitude"=> "8.038153",
                                        "longitude"=> "98.33313"
                                 ),
                        "sakhu"=>array(
                                        "latitude"=> "8.092542",
                                        "longitude"=> "98.304977"
                                 ),
                        "สาคู"=>array(
                                        "latitude"=> "8.092542",
                                        "longitude"=> "98.304977"
                                 )
                 );


    if (array_key_exists($key, $reference)) {
      $result = $reference[$key];
    }

    //var_dump($result); exit;

    return $result;
    
  }

?>