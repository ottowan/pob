<?php
  function Serialize_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition hotel
    ////////////////////////////////////////////
    $pntable['serialize_hotel'] = DBUtil::getLimitedTablename('serialize_hotel');
    $pntable['serialize_hotel_column'] = array(
                                          'id' => 'hotel_id',
                                          'name' => 'hotel_name',
                                          'code' => 'hotel_code',
                                          'information' => 'hotel_information'
    );
    $pntable['serialize_hotel_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'code' => 'TEXT  DEFAULT NULL',
                                          'information' => 'TEXT  DEFAULT NULL'
    );
    $pntable['serialize_hotel_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['serialize_hotel_column'], 'hotel_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['serialize_hotel_column_def']);

   return $pntable;
  }
?>