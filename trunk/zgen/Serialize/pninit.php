<?php
  function Serialize_init(){
    if (!DBUtil::createTable('serialize_hotel')) {
      return false;
    }
    return true;
  }

  function serialize_delete(){
    DBUtil::dropTable('serialize_hotel');
    return true;
  }
?>