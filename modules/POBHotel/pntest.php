<?php
function POBHotel_test_getContent(){
header("content-type: application/xml; charset=UTF-8");
  //load class
  $f = FormUtil::getPassedValue ('f', FALSE, 'REQUEST');
  $id = FormUtil::getPassedValue ('id', FALSE, 'REQUEST');
  if($id){
    $id = 1;
  }
  
  if (!($class = Loader::loadClass('HotelDescContentGenerator', "modules/POBHotel/pnincludes")))
  return LogUtil::registerError ('Unable to load class [HotelDescContentGenerator] ...');
  $obj = new HotelDescContentGenerator($id);

  if($f=="get"){
    $xml = $obj->getContent();
    print $xml->saveXML();
  }else if($f=="send"){
    $res = $$obj->sendContent();
    print $res;
  }

  pnShutDown();
}

?>