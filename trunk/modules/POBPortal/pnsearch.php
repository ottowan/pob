<?php
function POBPortal_search_searchResult(){

  $render = pnRender::getInstance('POBPortal');

  $form = FormUtil::getPassedValue ('form', FALSE, 'REQUEST');
  
  if(!$form['search']){
    $form['search'] = "phuket";
    $form['page'] = 1;
    $location  = "phuket";
    $distance  = "10";
    $latitude  = "7.88806";
    $longitude = "98.3975";
    $startDate = NULL;
    $endDate   = NULL;
  }

  //Send param to HotelSearch service 
  Loader::loadClass('HotelSearchEndpoint',"modules/POBPortal/pnincludes");
  $hotelSearch = new HotelSearchEndpoint();
  $hotelSearch->setHotelSearchXML( $location, $distance, $latitude, $longitude, NULL, NULL);

  //XML Response
  $response = $hotelSearch->getHotelSearchXML();
  //var_dump($response);

  //Convert xml response to array
  Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
  $reader = new POBReader();
  $arrayResponse = $reader->xmlToArray($response);
  //var_dump($arrayResponse);

  $repackArray = array();

  //echo count($arrayResponse["Properties"]["Properties"]);
  for($i=0; $i<count($arrayResponse["Properties"]["Properties"]); $i++){
    //Repack Relative attribute
    $repackArray[$i]["HotelCode"] = $arrayResponse["Properties"]["Properties"][$i]["@attributes"]["HotelCode"];
    $repackArray[$i]["HotelName"] = $arrayResponse["Properties"]["Properties"][$i]["@attributes"]["HotelName"];
    $repackArray[$i]["Description"] = $arrayResponse["Properties"]["Properties"][$i]["@attributes"]["Description"];
    //Repack Relative
    $repackArray[$i]["Direction"] = $arrayResponse["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Direction"];
    $repackArray[$i]["DistanceUnitName"] = $arrayResponse["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["DistanceUnitName"];
    $repackArray[$i]["Distance"] = $arrayResponse["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Distance"];
    $repackArray[$i]["Latitude"] = $arrayResponse["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Latitude"];
    $repackArray[$i]["Longitude"] = $arrayResponse["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Longitude"];

  }
  //var_dump($repackArray);
  //exit;
  //$objectArray = 
  $render->assign("objectArray",$repackArray );
  return $render->fetch('user_list_hotel.htm');


  //echo var_dump($form);
  //exit;

  //$keyword['search'] = "Ban";
  //$result = $getter->searchHotel($keyword);
  //if(count($result['data'])==19){
  //  $data['data'] = $result['data'];
  //}else{
  //  $data = $result['data'];
  //}
/*
  
  $render->assign("totalItems",$result['totalItems']);
  $render->assign("totalPages",$result['totalPages']);
  $render->assign("nowPage",$result['nowPage']);
  $render->assign("next",$result['next']);
  $render->assign("previous",$result['previous']);
  
  $render->assign("data",$data);
  if(FormUtil::getPassedValue ('source', FALSE, 'REQUEST')){
    var_dump($result);
    var_dump($keyword);
    exit;
  }


  $render->assign("totalItems",$result['totalItems']);
  $render->assign("totalPages",$result['totalPages']);
  $render->assign("nowPage",$result['nowPage']);
  $render->assign("next",$result['next']);
  $render->assign("previous",$result['previous']);

  return $render->fetch('user_list_hotel.htm');
*/
}
?>