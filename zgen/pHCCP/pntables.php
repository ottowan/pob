<?php
  function pHCCP_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition room
    ////////////////////////////////////////////
    $pntable['phccp_room'] = DBUtil::getLimitedTablename('phccp_room');
    $pntable['phccp_room_column'] = array(
                                          'id' => 'room_id',
                                          'name' => 'room_name',
                                          'description' => 'room_description'
    );
    $pntable['phccp_room_column_def'] = array(
                                          'id' => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'INT(11) DEFAULT NULL',
                                          'description' => 'VARCHAR(255) DEFAULT NULL'
    );
    $pntable['phccp_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['phccp_room_column'], 'room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['phccp_room_column_def']);

    ////////////////////////////////////////////
    //table definition facility
    ////////////////////////////////////////////
    $pntable['phccp_facility'] = DBUtil::getLimitedTablename('phccp_facility');
    $pntable['phccp_facility_column'] = array(
                                          'id' => 'facility_id',
                                          'name' => 'facility_name',
                                          'order' => 'facility_order'
    );
    $pntable['phccp_facility_column_def'] = array(
                                          'id' => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'VARCHAR(50) DEFAULT NULL',
                                          'order' => 'INT(2) DEFAULT NULL'
    );
    $pntable['phccp_facility_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['phccp_facility_column'], 'facility_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['phccp_facility_column_def']);

   return $pntable;
  }
?>