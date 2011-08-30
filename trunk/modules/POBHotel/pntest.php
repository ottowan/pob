<?php
function POBHotel_test_test(){
  if (!($class = Loader::loadClass('OTA_RoomCheckOut', "modules/POBHotel/pnincludes"))){
    return LogUtil::registerError ('Unable to load class [OTA_RoomCheckOut] ...');
  }
  
  $tester = new OTA_RoomCheckOut();
  $tester->checkOut("POBHT000033","2011-08-30","2011-08-30",2);
  $tester->getBookingReport();
  pnShutDown();
}

?>