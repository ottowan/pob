<?php
  function Inno_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition room
    ////////////////////////////////////////////
    $pntable['inno_room'] = DBUtil::getLimitedTablename('inno_room');
    $pntable['inno_room_column'] = array(
                                          'id' => 'room_id',
                                          'name' => 'room_name',
                                          'description' => 'room_description'
    );
    $pntable['inno_room_column_def'] = array(
                                          'id' => ' INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => ' VARCHAR(255)  DEFAULT NULL',
                                          'description' => ' VARCHAR(255)  DEFAULT NULL'
    );
    $pntable['inno_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['inno_room_column'], 'room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['inno_room_column_def']);

    ////////////////////////////////////////////
    //table definition facility
    ////////////////////////////////////////////
    $pntable['inno_facility'] = DBUtil::getLimitedTablename('inno_facility');
    $pntable['inno_facility_column'] = array(
                                          'id' => 'facility_id',
                                          'name' => 'facility_name',
                                          'order' => 'facility_order'
    );
    $pntable['inno_facility_column_def'] = array(
                                          'id' => ' INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => ' VARCHAR(255)  DEFAULT NULL',
                                          'order' => ' INT(11)  DEFAULT NULL'
    );
    $pntable['inno_facility_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['inno_facility_column'], 'facility_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['inno_facility_column_def']);

   return $pntable;
  }
?>