<?php
function POBHotel_test_test(){
  if (!($class = Loader::loadClass('OTA_RoomCheckOut', "modules/POBHotel/pnincludes"))){
    return LogUtil::registerError ('Unable to load class [OTA_RoomCheckOut] ...');
  }
  
  $tester = new OTA_RoomCheckOut();
  $tester->checkOut("POBHT000033","Superior","2011-09-01","2011-09-02",2);
  pnShutDown();
}

?>