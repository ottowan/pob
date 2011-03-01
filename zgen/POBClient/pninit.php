<?php
  function POBClient_init(){
    if (!DBUtil::createTable('pobclient_room')) {
      return false;
    }
    if (!DBUtil::createTable('pobclient_facility')) {
      return false;
    }
    return true;
  }

  function pobclient_delete(){
    DBUtil::dropTable('pobclient_room');
    DBUtil::dropTable('pobclient_facility');
    return true;
  }
?>