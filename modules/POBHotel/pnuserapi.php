<?php
function POBHotel_userapi_getLatLon($args) {
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelArray] ...');
      
    $objectArray = new $class;
    $objectArray->get();
    $hotelObject = $objectArray->_objData[0];
    
    $result["latitude"] = $hotelObject["position_latitude"];
    $result["longitude"] = $hotelObject["position_longitude"];
    
    return $result;
}

function POBHotel_userapi_getHotelCode($args) {
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelArray] ...');
      
    $objectArray = new $class;
    $objectArray->get();
    $hotelObject = $objectArray->_objData[0];
    
    $result["hotelcode"] = $hotelObject["code"];
    
    return $result;
}
?>