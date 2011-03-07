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
                                          'name' => 'hotel_name'
    );
    $pntable['pobclient_hotel_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'INT(11)  DEFAULT NULL'
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
                                          'description' => 'room_description'
    );
    $pntable['pobclient_room_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'description' => 'TEXT  DEFAULT NULL'
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
                                          'order' => 'facility_order'
    );
    $pntable['pobclient_facility_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'order' => 'INT(11)  DEFAULT NULL'
    );
    $pntable['pobclient_facility_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_facility_column'], 'facility_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_facility_column_def']);
    $pntable['pobclient_facility_column_idx'] = array(
                                          'idx_facility_hotel_id' =>'hotel_id'
    );

    ////////////////////////////////////////////
    //table definition seasons
    ////////////////////////////////////////////
    $pntable['pobclient_seasons'] = DBUtil::getLimitedTablename('pobclient_seasons');
    $pntable['pobclient_seasons_column'] = array(
                                          'id' => 'seasons_id',
                                          'hotel_id' => 'seasons_hotel_id',
                                          'room_id' => 'seasons_room_id',
                                          'startdate' => 'seasons_startdate',
                                          'enddate' => 'seasons_enddate'
    );
    $pntable['pobclient_seasons_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)  DEFAULT NULL',
                                          'room_id' => 'INT(11)  DEFAULT NULL',
                                          'startdate' => 'DATE  DEFAULT NULL',
                                          'enddate' => 'DATE  DEFAULT NULL'
    );
    $pntable['pobclient_seasons_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_seasons_column'], 'seasons_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_seasons_column_def']);
    $pntable['pobclient_seasons_column_idx'] = array(
                                          'idx_seasons_hotel_id' =>'hotel_id',
                                          'idx_seasons_room_id' =>'room_id'
    );

   return $pntable;
  }
?>