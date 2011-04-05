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
    if (!DBUtil::createTable('pobclient_season')) {
      return false;
    }
    if (!DBUtil::createTable('pobclient_customer')) {
      return false;
    }
    if (!DBUtil::createTable('pobclient_booking')) {
      return false;
    }
    return true;
  }

  function pobclient_delete(){
    DBUtil::dropTable('pobclient_hotel');
    DBUtil::dropTable('pobclient_room');
    DBUtil::dropTable('pobclient_facility');
    DBUtil::dropTable('pobclient_season');
    DBUtil::dropTable('pobclient_customer');
    DBUtil::dropTable('pobclient_booking');
    return true;
  }
?>