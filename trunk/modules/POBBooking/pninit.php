<?php

 /**
  * initialise the booking module
  *
  */
function POBBooking_init() {

  if (!DBUtil::createTable('pobbooking_customer')) {
      return false;
  }

  if (!DBUtil::createTable('pobbooking_booking')) {
      return false;
  }


  if (!DBUtil::createTable('pobbooking_status')) {
      return false;
  }

  POBBooking_Default_value();
  return true;
}

function POBBooking_delete() {
  // drop table
  DBUtil::dropTable('pobbooking_customer');
  DBUtil::dropTable('pobbooking_booking');
  DBUtil::dropTable('pobbooking_status');

  return true;
}

function POBBooking_Default_value() {
  // Default RoomType value
  $objArray =array();
  $objArray[] = array('id' => 1,'name' => 'finished');
  $objArray[] = array('id' => 2,'name' => 'new');
  $objArray[] = array('id' => 3,'name' => 'unpaid');
  $objArray[] = array('id' => 4,'name' => 'refund');
  DBUtil::insertObjectArray($objArray, 'pobbooking_status', true);
  unset($objArray);
}
