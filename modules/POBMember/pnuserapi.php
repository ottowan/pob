<?php
/////////////////////////////
//
//    $args = array("hotelcode"=>"POBHT000005");
//    pnModAPIFunc('POBMember', 'user', 'getDomainName', $args);
//
////////////////////////////
function POBMember_userapi_getDomainName($args) {
      if (!($class = Loader::loadClassFromModule ('POBMember', 'Member', false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');

      $object  = new $class ();
      $result = $object->get($args['hotelcode'], 'hotelcode');
      //print_r($object->_objData); exit;

    return $result;
}

?>