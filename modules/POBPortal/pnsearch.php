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
  $repackArray = repackArrayForDisplay($arrayResponse);
  //echo count($repackArray); exit;
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
    $repackArray = repackArrayForDisplay($arrayResponse);
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

function repackArrayForDisplay($originalArray){

  $repackArray = array();
  if($originalArray["Properties"]["Properties"]){
    /////////////////////////
    //Display multi item
    /////////////////////////
    //echo count($arrayResponse["Properties"]["Properties"]);
    for($i=0; $i<count($originalArray["Properties"]["Properties"]); $i++){

      $repackArray[$i]["startDate"] = $originalArray["startDate"];
      $repackArray[$i]["endDate"]   = $originalArray["endDate"];

      //Repack Hotel information
      $repackArray[$i]["HotelCode"] = $originalArray["Properties"]["Properties"][$i]["@attributes"]["HotelCode"];
      $repackArray[$i]["HotelName"] = $originalArray["Properties"]["Properties"][$i]["@attributes"]["HotelName"];
      $repackArray[$i]["Description"] = $originalArray["Properties"]["Properties"][$i]["@attributes"]["Description"];

      //Repack Relative position
      $repackArray[$i]["Direction"] = $originalArray["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Direction"];
      $repackArray[$i]["DistanceUnitName"] = $originalArray["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["DistanceUnitName"];
      $repackArray[$i]["Distance"] = number_format(mileToKilometre($originalArray["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Distance"]), 2);
      $repackArray[$i]["Latitude"] = $originalArray["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Latitude"];
      $repackArray[$i]["Longitude"] = $originalArray["Properties"]["Properties"][$i]["RelativePosition"]["RelativePosition"]["Longitude"];

      //Repack ContactInfo
      $repackArray[$i]["AddressLine"] = $originalArray["Properties"]["Properties"][$i]["ContactInfo"]["ContactInfo"]["AddressLine"];
      $repackArray[$i]["CityName"] = $originalArray["Properties"]["Properties"][$i]["ContactInfo"]["ContactInfo"]["CityName"];
      $repackArray[$i]["CountryName"] = $originalArray["Properties"]["Properties"][$i]["ContactInfo"]["ContactInfo"]["CountryName"];
      $repackArray[$i]["PhoneNumber"] = $originalArray["Properties"]["Properties"][$i]["ContactInfo"]["ContactInfo"]["PhoneNumber"];
      $repackArray[$i]["PostalCode"] = $originalArray["Properties"]["Properties"][$i]["ContactInfo"]["ContactInfo"]["PostalCode"];
      $repackArray[$i]["StateProv"] = $originalArray["Properties"]["Properties"][$i]["ContactInfo"]["ContactInfo"]["StateProv"];

      $repackArray[$i]["startDate"] = $originalArray["startDate"];
      $repackArray[$i]["endDate"]   = $originalArray["endDate"];

      //$repackArray[$i]["Availabilities"] =  $originalArray["Properties"]["Properties"][0]["Availabilities"]["Availabilities"];

      //var_dump($originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"]); exit;
      if($originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"]){
          for($j=0; $j<count($originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"]); $j++){
            $repackArray[$i]["Availabilities"][$j]["Date"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"][$j]["Availability"]["Date"];
            $repackArray[$i]["Availabilities"][$j]["InvCode"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"][$j]["Availability"]["InvCode"];
            $repackArray[$i]["Availabilities"][$j]["Limit"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"][$j]["Availability"]["Limit"];
            $repackArray[$i]["Availabilities"][$j]["Rate"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"][$j]["Availability"]["Rate"];
            $repackArray[$i]["Availabilities"][$j]["RatePlanCode"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availabilities"][$j]["Availability"]["RatePlanCode"];

          }//End loop  : loop Availabilities array

      //End if : check Availabilities array
      }else if($originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availability"]){
            //echo "unknown";
            $repackArray[$i]["Availabilities"][0]["Date"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availability"]["Availability"]["Date"];
            $repackArray[$i]["Availabilities"][0]["InvCode"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availability"]["Availability"]["InvCode"];
            $repackArray[$i]["Availabilities"][0]["Limit"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availability"]["Availability"]["Limit"];
            $repackArray[$i]["Availabilities"][0]["Rate"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availability"]["Availability"]["Rate"];
            $repackArray[$i]["Availabilities"][0]["RatePlanCode"] = $originalArray["Properties"]["Properties"][$i]["Availabilities"]["Availability"]["Availability"]["RatePlanCode"];

     }//End if : check Availability array


    ///////////////////////////////////
    // Array 1 : MultimediaDescriptions
    // Array 2 : ImageItems
    // Array 3 : ImageFormat
    ///////////////////////////////////

      if($originalArray["Properties"]["Properties"][$i]["MultimediaDescriptions"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"]){
        $repackArray[$i]["ImageItems"][0]["url"] = $originalArray["Properties"]["Properties"][4]["MultimediaDescriptions"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"][0]["ImageFormat"][0]["URL"];
      } //End check image
    }//End loop  : loop Properties array


//End if : check Properties array
  }else if($originalArray["Properties"]["Property"]["RelativePosition"]["RelativePosition"]["Distance"] == 0 && $originalArray["Properties"]["Property"]){
      /////////////////////////
      //Display one item
      /////////////////////////
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
      return $repackArray;
  }

  //var_dump($originalArray["Properties"]["Properties"][4]["MultimediaDescriptions"]["MultimediaDescriptions"][3]["ImageItems"]["ImageItems"][0]["ImageFormat"][0]["URL"]); exit;


  return $repackArray;

}


function mileToKilometre($mile){
  return ($mile * 1.609344);
}
?>