<?php
  function POBClient_init(){
    if (!DBUtil::createTable('pobclient_hotel')) {
      return false;
    }
    if (!DBUtil::createTable('pobclient_room')) {
      return false;
    }
    if (!DBUtil::createTable('pobclient_facility')) {
      return false;
    }
    if (!DBUtil::createTable('pobclient_seasons')) {
      return false;
    }
    return true;
  }

  function pobclient_delete(){
    DBUtil::dropTable('pobclient_hotel');
    DBUtil::dropTable('pobclient_room');
    DBUtil::dropTable('pobclient_facility');
    DBUtil::dropTable('pobclient_seasons');
    return true;
  }
?>