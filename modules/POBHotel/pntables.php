<?php
  function POBHotel_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition hotel
    ////////////////////////////////////////////
    $pntable['pobhotel_hotel'] = DBUtil::getLimitedTablename('pobhotel_hotel');
    $pntable['pobhotel_hotel_column'] = array(
                                          'id' => 'hotel_id',
                                          'name' => 'hotel_name',
                                          'status_id' => 'hotel_status_id',
                                          'rating' =>'hotel_rating',
                                          'start' =>'hotel_start',
                                          'when_built' => 'hotel_when_built',
                                          'descriptions'=> 'hotel_descriptions',
                                          'position_latitude'=> 'hotel_position_latitude',
                                          'position_longitude'=> 'hotel_position_longitude'
    );
    $pntable['pobhotel_hotel_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'status_id' => 'INT(11)',
                                          'rating' => 'INT(11)',
                                          'start' => 'DATE',
                                          'when_built'=> 'DATE',
                                          'descriptions' => 'TEXT  DEFAULT NULL',
                                          'position_latitude' => 'DOUBLE',
                                          'position_longitude' => 'DOUBLE'
    );
    $pntable['pobhotel_hotel_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_column'], 'hotel_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_column_def']);
    $pntable['pobhotel_hotel_column_idx'] = array(
                                          'idx_hotel_position_latitude' => 'position_latitude',
                                          'idx_hotel_position_longitude' => 'position_longitude'
    );

////////////////////////////////////////////////////////////////////////////////////////

    $pntable['pobhotel_hotel_amenity'] = DBUtil::getLimitedTablename('pobhotel_hotel_amenity');
    $pntable['pobhotel_hotel_amenity_column'] = array(
                                          'id' => 'hotel_amenity_id',
                                          'hotel_id' => 'hotel_amenity_hotel_id',
                                          'amenity_id' => 'hotel_amenity_amenity_id'
    );
    $pntable['pobhotel_hotel_amenity_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)',
                                          'amenity_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_amenity_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_amenity_column'], 'hotel_amenity_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_amenity_column_def']);
    $pntable['pobhotel_hotel_amenity_column_idx'] = array(
                                          'idx_hotel_amenity_hotel_id' => 'hotel_id',
                                          'idx_hotel_amenity_amenity_id' => 'amenity_id'
    );



    ////////////////////////////////////////////////////////////////////////////////////////
    // amenity table is a data of Hotel Amenity Code(HAC) on OpenTravel_CodeList_2010_12_2.xls file
    $pntable['pobhotel_amenity'] = DBUtil::getLimitedTablename('pobhotel_amenity');
    $pntable['pobhotel_amenity_column'] = array(
                                          'id' => 'amenity_id',
                                          'name' => 'amenity_name'
    );
    $pntable['pobhotel_amenity_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_amenity_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_amenity_column'], 'amenity_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_amenity_column_def']);

////////////////////////////////////////////////////////////////////////////////////////

    $pntable['pobhotel_status'] = DBUtil::getLimitedTablename('pobhotel_status');
    $pntable['pobhotel_status_column'] = array(
                                          'id' => 'status_id',
                                          'name' => 'status_name'
    );
    $pntable['pobhotel_status_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' => 'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_status_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_status_column'], 'status_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_status_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    // amenity table is a data of Hotel Amenity Code(HAC) on OpenTravel_CodeList_2010_12_2.xls file
    $pntable['pobhotel_location_category'] = DBUtil::getLimitedTablename('pobhotel_location_category');
    $pntable['pobhotel_location_category_column'] = array(
                                          'id' => 'location_category_id',
                                          'name' => 'location_category_name'
    );
    $pntable['pobhotel_location_category_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_location_category_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_location_category_column'], 'location_category_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_location_category_column_def']);


////////////////////////////////////////////////////////////////////////////////////////

    $pntable['pobhotel_hotel_location'] = DBUtil::getLimitedTablename('pobhotel_hotel_location');
    $pntable['pobhotel_hotel_location_column'] = array(
                                          'id' => 'hotel_location_id',
                                          'hotel_id' => 'hotel_location_hotel_id',
                                          'location_category_id' => 'hotel_location_location_category_id'
    );
    $pntable['pobhotel_hotel_location_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)',
                                          'location_category_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_location_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_location_column'], 'hotel_location_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_location_column_def']);
    $pntable['pobhotel_hotel_location_column_idx'] = array(
                                          'idx_hotel_hotel_location_hotel_id' => 'hotel_id',
                                          'idx_hotel_hotel_location_location_category_id' => 'location_category_id'
    );
////////////////////////////////////////////
    $pntable['pobhotel_hotel_image'] = DBUtil::getLimitedTablename('pobhotel_hotel_image');
    $pntable['pobhotel_hotel_image_column'] = array(
                                          'id' => 'hotel_image_id',
                                          'hotel_id' => 'hotel_image_hotel_id',
                                          'filename' => 'hotel_image_filename',
                                          'filesize' => 'hotel_image_filesize',
                                          'filetype' => 'hotel_image_filetype',
                                          'filepath' => 'hotel_image_filepath',
                                          'thumbname' => 'hotel_image_thumbname',
                                          'thumbsize' => 'hotel_image_thumbsize',
                                          'thumbtype' => 'hotel_image_thumbtype',
                                          'thumbpath' => 'hotel_image_thumbpath'
    );
    $pntable['pobhotel_hotel_image_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' => 'INT(11)',
                                          'filename' => 'TEXT  DEFAULT NULL',
                                          'filesize' => 'TEXT  DEFAULT NULL',
                                          'filetype' => 'TEXT  DEFAULT NULL',
                                          'filepath' => 'TEXT  DEFAULT NULL',
                                          'thumbname' => 'TEXT  DEFAULT NULL',
                                          'thumbsize' => 'TEXT  DEFAULT NULL',
                                          'thumbtype' => 'TEXT  DEFAULT NULL',
                                          'thumbpath' => 'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_hotel_image_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_image_column'], 'hotel_image_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_image_column_def']);
    $pntable['pobhotel_hotel_image_column_idx'] = array(
                                          'idx_hotel_hotel_image_hotel_id' => 'hotel_id',
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_address_use_type'] = DBUtil::getLimitedTablename('pobhotel_address_use_type');
    $pntable['pobhotel_address_use_type_column'] = array(
                                          'id' => 'address_use_type_id',
                                          'name' => 'address_use_type_name'
    );
    $pntable['pobhotel_address_use_type_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_address_use_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_address_use_type_column'], 'address_use_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_address_use_type_column_def']);


////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_attraction_category'] = DBUtil::getLimitedTablename('pobhotel_attraction_category');
    $pntable['pobhotel_attraction_category_column'] = array(
                                          'id' => 'attraction_category_id',
                                          'name' => 'attraction_category_name'
    );
    $pntable['pobhotel_attraction_category_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_attraction_category_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_attraction_category_column'], 'attraction_category_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_attraction_category_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_fee_tax_type'] = DBUtil::getLimitedTablename('pobhotel_fee_tax_type');
    $pntable['pobhotel_fee_tax_type_column'] = array(
                                          'id' => 'fee_tax_type_id',
                                          'name' => 'fee_tax_type_name'
    );
    $pntable['pobhotel_fee_tax_type_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_fee_tax_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_fee_tax_type_column'], 'fee_tax_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_fee_tax_type_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_index_point'] = DBUtil::getLimitedTablename('pobhotel_index_point');
    $pntable['pobhotel_index_point_column'] = array(
                                          'id' => 'index_point_id',
                                          'name' => 'index_point_name'
    );
    $pntable['pobhotel_index_point_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_index_point_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_index_point_column'], 'index_point_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_index_point_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_main_cuisine'] = DBUtil::getLimitedTablename('pobhotel_main_cuisine');
    $pntable['pobhotel_main_cuisine_column'] = array(
                                          'id' => 'main_cuisine_id',
                                          'name' => 'main_cuisine_name'
    );
    $pntable['pobhotel_main_cuisine_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_main_cuisine_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_main_cuisine_column'], 'main_cuisine_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_main_cuisine_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_meeting_room'] = DBUtil::getLimitedTablename('pobhotel_meeting_room');
    $pntable['pobhotel_meeting_room_column'] = array(
                                          'id' => 'meeting_room_id',
                                          'name' => 'meeting_room_name'
    );
    $pntable['pobhotel_meeting_room_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_meeting_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_meeting_room_column'], 'meeting_room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_meeting_room_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_meeting_room_format'] = DBUtil::getLimitedTablename('pobhotel_meeting_room_format');
    $pntable['pobhotel_meeting_room_format_column'] = array(
                                          'id' => 'meeting_room_format_id',
                                          'name' => 'meeting_room_format_name'
    );
    $pntable['pobhotel_meeting_room_format_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_meeting_room_format_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_meeting_room_format_column'], 'meeting_room_format_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_meeting_room_format_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_payment_type'] = DBUtil::getLimitedTablename('pobhotel_payment_type');
    $pntable['pobhotel_payment_type_column'] = array(
                                          'id' => 'payment_type_id',
                                          'name' => 'payment_type_name'
    );
    $pntable['pobhotel_payment_type_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_payment_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_payment_type_column'], 'payment_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_payment_type_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_recreation_srvc_detail'] = DBUtil::getLimitedTablename('pobhotel_recreation_srvc_detail');
    $pntable['pobhotel_recreation_srvc_detail_column'] = array(
                                          'id' => 'recreation_srvc_detail_id',
                                          'name' => 'recreation_srvc_detail_name'
    );
    $pntable['pobhotel_recreation_srvc_detail_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_recreation_srvc_detail_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_recreation_srvc_detail_column'], 'recreation_srvc_detail_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_recreation_srvc_detail_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_recreation_srvc_type'] = DBUtil::getLimitedTablename('pobhotel_recreation_srvc_type');
    $pntable['pobhotel_recreation_srvc_type_column'] = array(
                                          'id' => 'recreation_srvc_type_id',
                                          'name' => 'recreation_srvc_type_name'
    );
    $pntable['pobhotel_recreation_srvc_type_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_recreation_srvc_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_recreation_srvc_type_column'], 'recreation_srvc_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_recreation_srvc_type_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_restaurant_category'] = DBUtil::getLimitedTablename('pobhotel_restaurant_category');
    $pntable['pobhotel_restaurant_category_column'] = array(
                                          'id' => 'restaurant_category_id',
                                          'name' => 'restaurant_category_name'
    );
    $pntable['pobhotel_restaurant_category_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_restaurant_category_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_restaurant_category_column'], 'restaurant_category_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_restaurant_category_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_room_amenity_type'] = DBUtil::getLimitedTablename('pobhotel_room_amenity_type');
    $pntable['pobhotel_room_amenity_type_column'] = array(
                                          'id' => 'room_amenity_type_id',
                                          'name' => 'room_amenity_type_name'
    );
    $pntable['pobhotel_room_amenity_type_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_room_amenity_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_room_amenity_type_column'], 'room_amenity_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_room_amenity_type_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_segment_category'] = DBUtil::getLimitedTablename('pobhotel_segment_category');
    $pntable['pobhotel_segment_category_column'] = array(
                                          'id' => 'segment_category_id',
                                          'name' => 'segment_category_name'
    );
    $pntable['pobhotel_segment_category_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_segment_category_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_segment_category_column'], 'segment_category_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_segment_category_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_transportation'] = DBUtil::getLimitedTablename('pobhotel_transportation');
    $pntable['pobhotel_transportation_column'] = array(
                                          'id' => 'transportation_id',
                                          'name' => 'transportation_name'
    );
    $pntable['pobhotel_transportation_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_transportation_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_transportation_column'], 'transportation_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_transportation_column_def']);


    return $pntable;
  }