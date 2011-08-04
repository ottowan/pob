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
    $repackArray = repackArrayForDisplay($arrayResponse);

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

function repackArrayForDisplay($originalArray){
  $distanceValidate = floor($originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"]);
  $repackArray = array();
  if($distanceValidate == 0 && $originalArray["Properties"]["Property"]){

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
      $repackArray["Distance"]         = number_format(mileToKilometre($originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"]), 2);
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

      }

      //var_dump($repackArray["ImageItems"][$k]["ImageItem"]); exit;

    }
      //var_dump($repackArray["ImageItems"][0]["category"]); exit;
      //return $repackArray;
  }

  //var_dump($originalArray["Properties"]["Properties"][4]["MultimediaDescriptions"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"][0]["ImageFormat"][0]["URL"]); exit;


  return $repackArray;

}


function mileToKilometre($mile){
  return ($mile * 1.609344);
}
?>