<?php
  function Inno_init(){
    if (!DBUtil::createTable('inno_room')) {
      return false;
    }
    if (!DBUtil::createTable('inno_facility')) {
      return false;
    }
    return true;
  }

  function inno_delete(){
    DBUtil::dropTable('inno_room');
    DBUtil::dropTable('inno_facility');
    return true;
  }
?>