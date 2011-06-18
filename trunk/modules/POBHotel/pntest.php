<?php
function POBHotel_test_getContent(){
header("content-type: application/xml; charset=UTF-8");
  //load class
  if (!($class = Loader::loadClass('HotelDescContentGenerator', "modules/POBHotel/pnincludes")))
  return LogUtil::registerError ('Unable to load class [HotelDescContentGenerator] ...');
  $obj = new HotelDescContentGenerator(1);
  $xml = $obj->getContent();
  print $xml->saveXML();
  pnShutDown();
}

?>