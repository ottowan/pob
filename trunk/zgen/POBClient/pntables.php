<?php
  function POBClient_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition room
    ////////////////////////////////////////////
    $pntable['pobclient_room'] = DBUtil::getLimitedTablename('pobclient_room');
    $pntable['pobclient_room_column'] = array(
                                          'id' => 'room_id',
                                          'name' => 'room_name',
                                          'description' => 'room_description'
    );
    $pntable['pobclient_room_column_def'] = array(
                                          'id' => ' INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => ' VARCHAR(255)  DEFAULT NULL',
                                          'description' => ' VARCHAR(255)  DEFAULT NULL'
    );
    $pntable['pobclient_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_room_column'], 'room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_room_column_def']);

    ////////////////////////////////////////////
    //table definition facility
    ////////////////////////////////////////////
    $pntable['pobclient_facility'] = DBUtil::getLimitedTablename('pobclient_facility');
    $pntable['pobclient_facility_column'] = array(
                                          'id' => 'facility_id',
                                          'name' => 'facility_name',
                                          'order' => 'facility_order'
    );
    $pntable['pobclient_facility_column_def'] = array(
                                          'id' => ' INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => ' VARCHAR(255)  DEFAULT NULL',
                                          'order' => ' INT(11)  DEFAULT NULL'
    );
    $pntable['pobclient_facility_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobclient_facility_column'], 'facility_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobclient_facility_column_def']);

   return $pntable;
  }
?>