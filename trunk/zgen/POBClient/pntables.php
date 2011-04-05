<?php
  function POBClient_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition hotel
    ////////////////////////////////////////////
    $pntable['pobclient_hotel'] = DBUtil::getLimitedTablename('pobclient_hotel');
    $pntable['pobclient_hotel_column'] = array(
                                          'id' => 'hotel_id',
                                          'name' => 'hotel_name',
                                          'code' => 'hotel_code',
                                          'information' => 'hotel_information',
    );
    $pntable['pobclient_hotel_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'code' => 'TEXT  DEFAULT NULL',
                                          'information' => 'TEXT  DEFAULT NULL',
    );
    $pntable['pobclient_hotel_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_hotel_column'], 'hotel_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_hotel_column_def']);

    ////////////////////////////////////////////
    //table definition room
    ////////////////////////////////////////////
    $pntable['pobclient_room'] = DBUtil::getLimitedTablename('pobclient_room');
    $pntable['pobclient_room_column'] = array(
                                          'id' => 'room_id',
                                          'hotel_id' => 'room_hotel_id',
                                          'name' => 'room_name',
                                          'description' => 'room_description',
                                          'price' => 'room_price'
    );
    $pntable['pobclient_room_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'description' => 'TEXT  DEFAULT NULL',
                                          'price' => 'FLOAT  DEFAULT NULL'
    );
    $pntable['pobclient_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_room_column'], 'room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_room_column_def']);
    $pntable['pobclient_room_column_idx'] = array(
                                          'idx_room_hotel_id' =>'hotel_id'
    );

    ////////////////////////////////////////////
    //table definition facility
    ////////////////////////////////////////////
    $pntable['pobclient_facility'] = DBUtil::getLimitedTablename('pobclient_facility');
    $pntable['pobclient_facility_column'] = array(
                                          'id' => 'facility_id',
                                          'hotel_id' => 'facility_hotel_id',
                                          'name' => 'facility_name',
                                          'order' => 'facility_order',
    );
    $pntable['pobclient_facility_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'order' => 'INT(11)  DEFAULT NULL',
    );
    $pntable['pobclient_facility_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_facility_column'], 'facility_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_facility_column_def']);
    $pntable['pobclient_facility_column_idx'] = array(
                                          'idx_facility_hotel_id' =>'hotel_id'
    );

    ////////////////////////////////////////////
    //table definition season
    ////////////////////////////////////////////
    $pntable['pobclient_season'] = DBUtil::getLimitedTablename('pobclient_season');
    $pntable['pobclient_season_column'] = array(
                                          'id' => 'season_id',
                                          'hotel_id' => 'season_hotel_id',
                                          'room_id' => 'season_room_id',
                                          'name' => 'season_name',
                                          'startdate' => 'season_startdate',
                                          'enddate' => 'season_enddate',
    );
    $pntable['pobclient_season_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'room_id' => 'INT(11)  DEFAULT NULL',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'startdate' => 'DATE  DEFAULT NULL',
                                          'enddate' => 'DATE  DEFAULT NULL',
    );
    $pntable['pobclient_season_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_season_column'], 'season_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_season_column_def']);
    $pntable['pobclient_season_column_idx'] = array(
                                          'idx_season_hotel_id' =>'hotel_id',
                                          'idx_season_room_id' =>'room_id'
    );

    ////////////////////////////////////////////
    //table definition customer
    ////////////////////////////////////////////
    $pntable['pobclient_customer'] = DBUtil::getLimitedTablename('pobclient_customer');
    $pntable['pobclient_customer_column'] = array(
                                          'id' => 'customer_id',
                                          'firstname' => 'customer_firstname',
                                          'lastname' => 'customer_lastname'
    );
    $pntable['pobclient_customer_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'firstname' => 'INT(11)  DEFAULT NULL',
                                          'lastname' => 'INT(11)  DEFAULT NULL'
    );
    $pntable['pobclient_customer_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_customer_column'], 'customer_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_customer_column_def']);

    ////////////////////////////////////////////
    //table definition booking
    ////////////////////////////////////////////
    $pntable['pobclient_booking'] = DBUtil::getLimitedTablename('pobclient_booking');
    $pntable['pobclient_booking_column'] = array(
                                          'id' => 'booking_id',
                                          'customer_id' => 'booking_customer_id',
                                          'hotel_id' => 'booking_hotel_id',
                                          'room_id' => 'booking_room_id',
                                          'price' => 'booking_price',
    );
    $pntable['pobclient_booking_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'customer_id' => 'INT(11)  DEFAULT NULL',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'room_id' => 'INT(11)  DEFAULT NULL',
                                          'price' => 'FLOAT  DEFAULT NULL',
    );
    $pntable['pobclient_booking_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_booking_column'], 'booking_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_booking_column_def']);
    $pntable['pobclient_booking_column_idx'] = array(
                                          'idx_booking_customer_id' =>'customer_id',
                                          'idx_booking_hotel_id' =>'hotel_id',
                                          'idx_booking_room_id' =>'room_id'
    );

   return $pntable;
  }
?>