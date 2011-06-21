<?php
function selectExtendResult($id=NULL){
      if(is_null($id)){
        return FALSE;
      }
      $result = array();
      if ($id){
      $fieldArray = array('amenity_id');

      $result['hotelAmenity'] = DBUtil::selectObjectArray( 'pobhotel_hotel_amenity',
                                                                "WHERE hotel_amenity_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldArray
      );


      $fieldArray = array('attraction_id');
      $result['hotelAttraction'] = DBUtil::selectObjectArray( 'pobhotel_hotel_attraction',
                                                                "WHERE hotel_attraction_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldArray
      );

      $fieldImageArray = array('id','filepath','filename');
      $result['imageHotel'] = DBUtil::selectObjectArray( 'pobhotel_hotel_image',
                                                                "WHERE hotel_image_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldImageArray
      );
      
      
      
      $fieldLocationArray = array('location_category_id');

      $result['locationCategory'] = DBUtil::selectObjectArray( 'pobhotel_hotel_location',
                                                                "WHERE hotel_location_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldLocationArray
      );

      }

      return $result;
    }
function getAmenityName($id=NULL){
  if(is_null($id)){
    return FALSE;
  }
  $fieldArray = array('name');
  $result = DBUtil::selectObjectArray( 'pobhotel_amenity',
                                                      "WHERE amenity_id = '$id'",
                                                      '',
                                                      -1,
                                                      -1,
                                                      '',
                                                      null,
                                                      null,
                                                      $fieldArray
  );
  return $result[0]["name"];
}

function getAttractionName($id=NULL){
  if(is_null($id)){
    return FALSE;
  }
  $fieldArray = array('name');
  $result = DBUtil::selectObjectArray( 'pobhotel_attraction',
                                                      "WHERE attraction_id = '$id'",
                                                      '',
                                                      -1,
                                                      -1,
                                                      '',
                                                      null,
                                                      null,
                                                      $fieldArray
  );
  return $result[0]["name"];
}

function getLocationName($id=NULL){
  if(is_null($id)){
    return FALSE;
  }
  $fieldArray = array('name');
  $result = DBUtil::selectObjectArray( 'pobhotel_location_category',
                                                      "WHERE location_category_id = '$id'",
                                                      '',
                                                      -1,
                                                      -1,
                                                      '',
                                                      null,
                                                      null,
                                                      $fieldArray
  );
  return $result[0]["name"];
}
function POBHotel_ws_getHotelDetailById(){

  $id = FormUtil::getPassedValue ('id', FALSE, 'REQUEST');
  $desc  = FormUtil::getPassedValue ('desc', FALSE, 'REQUEST');
  $asc  = FormUtil::getPassedValue ('asc', FALSE, 'REQUEST');
  $page = FormUtil::getPassedValue ('page', 1, 'REQUEST');
  $pageSize = 100;
  
  if(!$id){
    header("Content-type: text/xml");
    $xml = new DOMDocument("1.0", "utf-8" );
    $xml->preserveWhiteSpace = false; 
    $xml->formatOutput = true;
    
    $xmlDatas = $xml->createElement("datas");

    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","argument [id] is required.");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }else if(!is_numeric($id)){
    header("Content-type: text/xml");
    $xml = new DOMDocument("1.0", "utf-8" );
    $xml->preserveWhiteSpace = false; 
    $xml->formatOutput = true;
    
    $xmlDatas = $xml->createElement("datas");

    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","Invalid ardument: [id].");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }
  
  if (!($class = Loader::loadClassFromModule ('POBHotel','HotelArray', false)))
  return LogUtil::registerError ("Unable to load class [HotelArray] ...");

  
  $objectArray = new $class ();
  $where   = null;
  $sort = null;
  

  if($id){
    if (method_exists($objectArray,'genFilter')){
      $where = $objectArray->genFilter();
    }
  }
  
  if(($desc)||($asc)){
    if (method_exists($objectArray,'genSort')){
      $sort = $objectArray->genSort();
    }
  }
  
  if(empty($sort)){
    $sort = $objectArray->_objSort;
  }
  
  //Spilt page
  $totalItems  = $objectArray->getCount($where , true);
  $totalPages  = ceil($totalItems/$pageSize);
  $nowPage = $page;
  if($page>0){
    $page--;
  }
  $page = $page*$pageSize;
  
  $objectArray->get($where, $sort, $page, $pageSize);
  $objDataArray = $objectArray->_objData;

  
  header("Content-type: text/xml");
  $xml = new DOMDocument("1.0", "utf-8" );
  $xml->preserveWhiteSpace = false; 
  $xml->formatOutput = true;
  
  $xmlDatas = $xml->createElement("datas");
  
  if(empty($objDataArray))
  {
    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","ไม่มีข้อมูล");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }
  
  $previous = $nowPage<=1 ? "" : $nowPage-1;
  $next = $nowPage>=$totalPages ? "" : $nowPage+1;
  //Assign attribute for generate xml
  $objDataArray['totalItems'] = $totalItems;
  $objDataArray['totalPages'] = $totalPages;
  $objDataArray['nowPage'] = $nowPage;
  $objDataArray['next'] = $next;
  $objDataArray['previous'] = $previous;

  
  foreach($objDataArray as $key1=>$val1)
  {
    $xmlChild = $xml->createElement("data");
    if(is_array($val1)){
      foreach($val1 as $key2=>$val2){
        $xmlNode = $xml->createElement($key2,$val2);
        $xmlChild->appendChild($xmlNode);
      }
    }else{
      $xmlChild = $xml->createElement($key1,$val1);
    }

    $xmlDatas->appendChild($xmlChild);
  }
  
  $xml->appendChild($xmlDatas);
  
  print $xml->saveXML();
  pnShutDown();
  
  
}

function POBHotel_ws_getHotelList(){

  $list = FormUtil::getPassedValue ('list', FALSE, 'REQUEST');
  $desc  = FormUtil::getPassedValue ('desc', FALSE, 'REQUEST');
  $asc  = FormUtil::getPassedValue ('asc', FALSE, 'REQUEST');
  $page = FormUtil::getPassedValue ('page', 1, 'REQUEST');
  $pageSize = 100;

  if (!($class = Loader::loadClassFromModule ('POBHotel','HotelArray', false)))
  return LogUtil::registerError ("Unable to load class [HotelArray] ...");
  
  $objectArray = new $class ();
  $where   = null;
  $sort = null;
  
  if($list){
    if (method_exists($objectArray,'genFilter')){
      $where = $objectArray->genFilter();
    }
  }
  
  if(($desc)||($asc)){
    if (method_exists($objectArray,'genSort')){
      $sort = $objectArray->genSort();
    }
  }
  
  if(empty($sort)){
    $sort = $objectArray->_objSort;
  }
  
  //Spilt page
  $totalItems  = $objectArray->getCount($where , true);
  $totalPages  = ceil($totalItems/$pageSize);
  $nowPage = $page;
  if($page>0){
    $page--;
  }
  $page = $page*$pageSize;
  
  $objectArray->get($where, $sort, $page, $pageSize);
  $objDataArray = $objectArray->_objData;
  
  header("Content-type: text/xml");
  $xml = new DOMDocument("1.0", "utf-8" );
  $xml->preserveWhiteSpace = false; 
  $xml->formatOutput = true;
  
  $xmlDatas = $xml->createElement("datas");
  
  if(empty($objDataArray))
  {
    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","ไม่มีข้อมูล");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }

  
  

  
  foreach($objDataArray as $key1=>$val1){
    $extend = selectExtendResult($val1['id']);
    
    foreach($extend AS $ExKey=>$ExVal){
        $i=0;
       foreach($ExVal AS $ExKey2=>$ExVal2){
         if($ExKey=="hotelAmenity"){
           //$ExVal[$i]["id"] = $ExVal2["amenity_id"];
           $ExVal[$i++] = $ExVal2["amenity_id"].",".getAmenityName($ExVal2["amenity_id"]);
         }
         if($ExKey=="hotelAttraction"){
           $ExVal[$i++] = $ExVal2["attraction_id"].",".getAttractionName($ExVal2["attraction_id"]);
         }
         if($ExKey=="locationCategory"){
           $ExVal[$i++] = $ExVal2["location_category_id"].",".getLocationName($ExVal2["location_category_id"]);
         }
         if($ExKey=="imageHotel"){
           $ExVal[$i++] = $ExVal2["id"].",".$ExVal2["filepath"].$ExVal2["filename"];
         }
        $val1[$ExKey] = $ExVal;	
       }
       
    }
    $objDataArray2[] = $val1;
  }
 
  $previous = $nowPage<=1 ? "" : $nowPage-1;
  $next = $nowPage>=$totalPages ? "" : $nowPage+1;
  //Assign attribute for generate xml
  $objDataArray2['totalItems'] = $totalItems;
  $objDataArray2['totalPages'] = $totalPages;
  $objDataArray2['nowPage'] = $nowPage;
  $objDataArray2['next'] = $next;
  $objDataArray2['previous'] = $previous;
  
  foreach($objDataArray2 as $key1=>$val1)
  {
    $xmlChild = $xml->createElement("data");
    if(is_array($val1)){
      foreach($val1 as $key2=>$val2){
        if(is_array($val2)){
          $xmlChild2 = $xml->createElement($key2);
          foreach($val2 AS $key3=>$val3){
            $val3 = explode(",",$val3);
            $xmlNode = $xml->createElement("item",$val3[1]);
            $xmlNode->setAttribute("id",$val3[0]);
            $xmlChild2->appendChild($xmlNode);
            $xmlChild->appendChild($xmlChild2);
          }
        }else{
          $xmlNode = $xml->createElement($key2,$val2);
          $xmlChild->appendChild($xmlNode);
        }
      }
    }else{
       $xmlChild = $xml->createElement($key1,$val1);
    }
    
    $xmlDatas->appendChild($xmlChild);
  }
  
  $xml->appendChild($xmlDatas);

  print $xml->saveXML();

  pnShutDown();
  
  
}

function POBHotel_ws_getRoomListByHotelId(){

  $id = FormUtil::getPassedValue ('id', FALSE, 'REQUEST');
  $desc  = FormUtil::getPassedValue ('desc', FALSE, 'REQUEST');
  $asc  = FormUtil::getPassedValue ('asc', FALSE, 'REQUEST');
  $page = FormUtil::getPassedValue ('page', 1, 'REQUEST');
  $pageSize = 100;
  
  if(!$id){
    header("Content-type: text/xml");
    $xml = new DOMDocument("1.0", "utf-8" );
    $xml->preserveWhiteSpace = false; 
    $xml->formatOutput = true;
    
    $xmlDatas = $xml->createElement("datas");

    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","argument [id] is required.");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }else if(!is_numeric($id)){
    header("Content-type: text/xml");
    $xml = new DOMDocument("1.0", "utf-8" );
    $xml->preserveWhiteSpace = false; 
    $xml->formatOutput = true;
    
    $xmlDatas = $xml->createElement("datas");

    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","Invalid ardument: [id].");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }
  
  if (!($class = Loader::loadClassFromModule ('POBHotel','RoomArray', false)))
  return LogUtil::registerError ("Unable to load class [RoomArray] ...");
  
  $objectArray = new $class ();
  $where   = null;
  $sort = null;
  
  if($id){
    if (method_exists($objectArray,'genFilterRoomListByHotelId')){
      $where = $objectArray->genFilterRoomListByHotelId();
    }
  }
  
  if(($desc)||($asc)){
    if (method_exists($objectArray,'genSort')){
      $sort = $objectArray->genSort();
    }
  }
  
  if(empty($sort)){
    $sort = $objectArray->_objSort;
  }
  
  //Spilt page
  $totalItems  = $objectArray->getCount($where , true);
  $totalPages  = ceil($totalItems/$pageSize);
  $nowPage = $page;
  if($page>0){
    $page--;
  }
  $page = $page*$pageSize;

  $objectArray->get($where, $sort, $page, $pageSize);
  $objDataArray = $objectArray->_objData;
  
  header("Content-type: text/xml");
  $xml = new DOMDocument("1.0", "utf-8" );
  $xml->preserveWhiteSpace = false; 
  $xml->formatOutput = true;
  
  $xmlDatas = $xml->createElement("datas");
  
  if(empty($objDataArray))
  {
    $xmlChild = $xml->createElement("data");
    $xmlChild->setAttribute("id",0);
    $xmlTitle = $xml->createElement("title","ไม่มีข้อมูล");
    $xmlChild->appendChild($xmlTitle);
    $xmlDatas->appendChild($xmlChild);
    $xml->appendChild($xmlDatas);
  
    print $xml->saveXML();
    pnShutDown();
  }
  
  $previous = $nowPage<=1 ? "" : $nowPage-1;
  $next = $nowPage>=$totalPages ? "" : $nowPage+1;
  //Assign attribute for generate xml
  $objDataArray['totalItems'] = $totalItems;
  $objDataArray['totalPages'] = $totalPages;
  $objDataArray['nowPage'] = $nowPage;
  $objDataArray['next'] = $next;
  $objDataArray['previous'] = $previous;
  
  foreach($objDataArray as $key1=>$val1)
  {
    
    $xmlChild = $xml->createElement("data");
    if(is_array($val1)){
      foreach($val1 as $key2=>$val2){
        $xmlNode = $xml->createElement($key2,$val2);
        $xmlChild->appendChild($xmlNode);
      }
    }else{
      $xmlChild = $xml->createElement($key1,$val1);
    }

    $xmlDatas->appendChild($xmlChild);
  }
  
  $xml->appendChild($xmlDatas);
  
  print $xml->saveXML();
  pnShutDown();
  
  
}
?>