<?php
function POBHotel_test_test(){
  if (!($class = Loader::loadClass('OTA_HotelAvailNotifRQ', "modules/POBHotel/pnincludes"))){
    return LogUtil::registerError ('Unable to load class [OTA_HotelAvailNotifRQ] ...');
  }
  
  $tester = new OTA_HotelAvailNotifRQ();;
  
  $tester->getContent();
  pnShutDown();
}

?>