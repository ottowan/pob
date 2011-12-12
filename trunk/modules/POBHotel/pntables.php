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
                                          'code' => 'hotel_code',
                                          'name' => 'hotel_name',
                                          'name_en' => 'hotel_name_en',
                                          'status_id' => 'hotel_status_id',
                                          'rating' =>'hotel_rating',
                                          'start' =>'hotel_start',
                                          'when_built' => 'hotel_when_built',
                                          'descriptions'=> 'hotel_descriptions',
                                          'descriptions_en'=> 'hotel_descriptions_en',
                                          'policy' => 'hotel_policy',
                                          'policy_en' => 'hotel_policy_en',
                                          'position_latitude'=> 'hotel_position_latitude',
                                          'position_longitude'=> 'hotel_position_longitude',
                                          'address_line' => 'hotel_address_line',
                                          'city_name' => 'hotel_city_name',
                                          'state_province' => 'hotel_state_province',
                                          'postal_code' => 'hotel_postal_code',
                                          'country' => 'hotel_country',
                                          'phone_number' => 'hotel_phone_number',
                                          'mobile_number' => 'hotel_mobile_number',
                                          'fax_number' => 'hotel_fax_number',
                                          'email' => 'hotel_email'
    );
    $pntable['pobhotel_hotel_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'code' => 'VARCHAR(255)',
                                          'name' => 'TEXT  DEFAULT NULL',
                                          'name_en' => 'TEXT  DEFAULT NULL',
                                          'status_id' => 'INT(11)',
                                          'rating' => 'INT(11)',
                                          'start' => 'DATE',
                                          'when_built'=> 'DATE',
                                          'descriptions' => 'TEXT  DEFAULT NULL',
                                          'descriptions_en' => 'TEXT  DEFAULT NULL',
                                          'policy' => 'TEXT  DEFAULT NULL',
                                          'policy_en' => 'TEXT  DEFAULT NULL',
                                          'position_latitude' => 'DOUBLE',
                                          'position_longitude' => 'DOUBLE',
                                          'address_line' => 'VARCHAR(255)',
                                          'city_name' => 'VARCHAR(255)',
                                          'state_province' => 'VARCHAR(255)',
                                          'postal_code' => 'INT(5)',
                                          'country' => 'VARCHAR(255)',
                                          'phone_number' => 'VARCHAR(255)',
                                          'mobile_number' => 'VARCHAR(255)',
                                          'fax_number' => 'VARCHAR(255)',
                                          'email' => 'VARCHAR(255)'
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
                                          'amenity_id' => 'hotel_amenity_amenity_id',
                                          'description' => 'hotel_amenity_description'
    );
    $pntable['pobhotel_hotel_amenity_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'amenity_id' =>'INT(11)',
                                          'description' =>'VARCHAR(255)'
    );
    $pntable['pobhotel_hotel_amenity_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_amenity_column'], 'hotel_amenity_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_amenity_column_def']);
    $pntable['pobhotel_hotel_amenity_column_idx'] = array(
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
                                          'location_category_id' => 'hotel_location_location_category_id',
                                          'description' => 'hotel_location_description'
    );
    $pntable['pobhotel_hotel_location_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'location_category_id' =>'INT(11)',
                                          'description' =>'varchar(255)'
    );
    $pntable['pobhotel_hotel_location_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_location_column'], 'hotel_location_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_location_column_def']);
    $pntable['pobhotel_hotel_location_column_idx'] = array(
                                          'idx_hotel_hotel_location_location_category_id' => 'location_category_id'
    );
////////////////////////////////////////////
    $pntable['pobhotel_hotel_image'] = DBUtil::getLimitedTablename('pobhotel_hotel_image');
    $pntable['pobhotel_hotel_image_column'] = array(
                                          'id' => 'hotel_image_id',
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
    $pntable['pobhotel_attraction'] = DBUtil::getLimitedTablename('pobhotel_attraction');
    $pntable['pobhotel_attraction_column'] = array(
                                          'id' => 'attraction_id',
                                          'name' => 'attraction_name'
    );
    $pntable['pobhotel_attraction_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_attraction_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobs_category_column'], 'attraction_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_attraction_column_def']);

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

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_guest_room'] = DBUtil::getLimitedTablename('pobhotel_guest_room');
    $pntable['pobhotel_guest_room_column'] = array(
                                          'id' => 'guest_room_id',
                                          'name' => 'guest_room_name',
                                          'type_id' => 'guest_room_type_id',
                                          'season_id' => 'guest_room_season_id',
                                          'limit' => 'guest_room_limit',
                                          'available' => 'guest_room_available',
                                          'price' => 'guest_room_price',
                                          'capacity' => 'guest_room_capacity',
                                          'description' => 'guest_room_description'
    );
    $pntable['pobhotel_guest_room_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL',
                                          'type_id' =>'INT(11)',
                                          'season_id' =>'INT(11)',
                                          'limit' => 'INT(11)',
                                          'available' => 'INT(11)',
                                          'price' => 'VARCHAR(255)',
                                          'capacity' =>'INT(11)',
                                          'description' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_guest_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_guest_room_column'], 'guest_room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_guest_room_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_guest_room_type'] = DBUtil::getLimitedTablename('pobhotel_guest_room_type');
    $pntable['pobhotel_guest_room_type_column'] = array(
                                          'id' => 'guest_room_type_id',
                                          'name' => 'guest_room_type_name',
                                          'description' => 'guest_room_description'
    );
    $pntable['pobhotel_guest_room_type_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL',
                                          'description' =>'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_guest_room_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_guest_room_type_column'], 'guest_room_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_guest_room_type_column_def']);

////////////////////////////////////////////
    $pntable['pobhotel_guest_room_image'] = DBUtil::getLimitedTablename('pobhotel_guest_room_image');
    $pntable['pobhotel_guest_room_image_column'] = array(
                                          'id' => 'room_image_id',
                                          'room_id' => 'room_image_room_id',
                                          'filename' => 'room_image_filename',
                                          'filesize' => 'room_image_filesize',
                                          'filetype' => 'room_image_filetype',
                                          'filepath' => 'room_image_filepath',
                                          'thumbname' => 'room_image_thumbname',
                                          'thumbsize' => 'room_image_thumbsize',
                                          'thumbtype' => 'room_image_thumbtype',
                                          'thumbpath' => 'room_image_thumbpath'
    );
    $pntable['pobhotel_guest_room_image_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'room_id' => 'INT(11)',
                                          'filename' => 'TEXT  DEFAULT NULL',
                                          'filesize' => 'TEXT  DEFAULT NULL',
                                          'filetype' => 'TEXT  DEFAULT NULL',
                                          'filepath' => 'TEXT  DEFAULT NULL',
                                          'thumbname' => 'TEXT  DEFAULT NULL',
                                          'thumbsize' => 'TEXT  DEFAULT NULL',
                                          'thumbtype' => 'TEXT  DEFAULT NULL',
                                          'thumbpath' => 'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_guest_room_image_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_guest_room_image_column'], 'guest_room_image_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_guest_room_image_column_def']);
    $pntable['pobhotel_guest_room_image_column_idx'] = array(
                                          'idx_guest_room_image_room_id' => 'room_id',
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_season'] = DBUtil::getLimitedTablename('pobhotel_season');
    $pntable['pobhotel_season_column'] = array(
                                          'id' => 'season_id',
                                          'name' => 'season_name',
                                          'date_start' => 'season_date_start',
                                          'date_end' => 'season_date_end'

    );
    $pntable['pobhotel_season_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'name' =>'TEXT  DEFAULT NULL',
                                          'date_start' =>'DATE',
                                          'date_end' =>'DATE'
    );
    $pntable['pobhotel_season_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_season_column'], 'season_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_season_column_def']);

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_rate'] = DBUtil::getLimitedTablename('pobhotel_rate');
    $pntable['pobhotel_rate_column'] = array(
                                          'id' => 'rate_id',
                                          'season_id' => 'rate_season_id',
                                          'room_id' => 'rate_room_id',
                                          'room_rate' => 'rate_room_rate'
    );
    $pntable['pobhotel_rate_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'season_id' =>'INT(11)',
                                          'room_id' =>'INT(11)',
                                          'room_rate' =>'INT(11)'
    );
    $pntable['pobhotel_rate_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_rate_column'], 'rate_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_rate_column_def']);
    $pntable['pobhotel_rate_column_idx'] = array(
                                          'idx_rate_season_id' => 'season_id',
                                          'idx_rate_room_id' => 'room_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_index_point'] = DBUtil::getLimitedTablename('pobhotel_hotel_index_point');
    $pntable['pobhotel_hotel_index_point_column'] = array(
                                          'id' => 'hotel_index_point_id',
                                          'index_point_id' => 'hotel_index_point_index_point_id',
                                          'description' => 'hotel_index_point_description'
    );

    $pntable['pobhotel_hotel_index_point_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'index_point_id' =>'INT(11)',
                                          'description' => 'varchar(255)'
    );
    $pntable['pobhotel_hotel_index_point_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_index_point_column'], 'hotel_index_point_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_index_point_column_def']);
    $pntable['pobhotel_hotel_index_point_column_idx'] = array(
                                          'idx_hotel_index_point_index_point_id' => 'index_point_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_attraction'] = DBUtil::getLimitedTablename('pobhotel_hotel_attraction');
    $pntable['pobhotel_hotel_attraction_column'] = array(
                                          'id' => 'hotel_attraction_id',
                                          'attraction_id' => 'hotel_attraction_attraction_id',
                                          'description' => 'hotel_attraction_description'
    );

    $pntable['pobhotel_hotel_attraction_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'attraction_id' =>'INT(11)',
                                          'description' => 'VARCHAR(255)'
    );
    $pntable['pobhotel_hotel_attraction_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_attraction_column'], 'hotel_attraction_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_attraction_column_def']);
    $pntable['pobhotel_hotel_attraction_column_idx'] = array(
                                          'idx_hotel_attraction_attraction_id' => 'attraction_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_fee_tax'] = DBUtil::getLimitedTablename('pobhotel_hotel_fee_tax');
    $pntable['pobhotel_hotel_fee_tax_column'] = array(
                                          'id' => 'hotel_fee_tax_id',
                                          'fee_tax_id' => 'hotel_fee_tax_fee_tax_id'
    );

    $pntable['pobhotel_hotel_fee_tax_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'fee_tax_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_fee_tax_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_fee_tax_column'], 'hotel_fee_tax_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_fee_tax_column_def']);
    $pntable['pobhotel_hotel_fee_tax_column_idx'] = array(
                                          'idx_hotel_fee_tax_fee_tax_id' => 'fee_tax_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_main_cuisine'] = DBUtil::getLimitedTablename('pobhotel_hotel_main_cuisine');
    $pntable['pobhotel_hotel_main_cuisine_column'] = array(
                                          'id' => 'hotel_main_cuisine_id',
                                          'main_cuisine_id' => 'hotel_main_cuisine_main_cuisine_id'
    );

    $pntable['pobhotel_hotel_main_cuisine_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'main_cuisine_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_main_cuisine_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_main_cuisine_column'], 'hotel_main_cuisine_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_main_cuisine_column_def']);
    $pntable['pobhotel_hotel_main_cuisine_column_idx'] = array(
                                          'idx_hotel_main_cuisine_main_cuisine_id' => 'main_cuisine_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_meeting_room'] = DBUtil::getLimitedTablename('pobhotel_hotel_meeting_room');
    $pntable['pobhotel_hotel_meeting_room_column'] = array(
                                          'id' => 'hotel_meeting_room_id',
                                          'meeting_room_id' => 'hotel_meeting_room_meeting_room_id'
    );

    $pntable['pobhotel_hotel_meeting_room_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'meeting_room_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_meeting_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_meeting_room_column'], 'hotel_meeting_room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_meeting_room_column_def']);
    $pntable['pobhotel_hotel_meeting_room_column_idx'] = array(
                                          'idx_hotel_meeting_room_meeting_room_id' => 'meeting_room_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_payment_type'] = DBUtil::getLimitedTablename('pobhotel_hotel_payment_type');
    $pntable['pobhotel_hotel_payment_type_column'] = array(
                                          'id' => 'hotel_payment_type_id',
                                          'payment_type_id' => 'hotel_payment_type_payment_type_id'
    );

    $pntable['pobhotel_hotel_payment_type_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'payment_type_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_payment_type_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_payment_type_column'], 'hotel_payment_type_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_payment_type_column_def']);
    $pntable['pobhotel_hotel_payment_type_column_idx'] = array(
                                          'idx_hotel_payment_type_payment_type_id' => 'payment_type_id'
    );


////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_recreation_srvc'] = DBUtil::getLimitedTablename('pobhotel_hotel_recreation_srvc');
    $pntable['pobhotel_hotel_recreation_srvc_column'] = array(
                                          'id' => 'hotel_recreation_srvc_id',
                                          'recreation_srvc_id' => 'hotel_recreation_srvc_recreation_srvc_id'
    );

    $pntable['pobhotel_hotel_recreation_srvc_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'recreation_srvc_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_recreation_srvc_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_recreation_srvc_column'], 'hotel_recreation_srvc_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_recreation_srvc_column_def']);
    $pntable['pobhotel_hotel_recreation_srvc_column_idx'] = array(
                                          'idx_hotel_recreation_srvc_recreation_srvc_id' => 'recreation_srvc_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_restaurant'] = DBUtil::getLimitedTablename('pobhotel_hotel_restaurant');
    $pntable['pobhotel_hotel_restaurant_column'] = array(
                                          'id' => 'hotel_restaurant_id',
                                          'restaurant_id' => 'hotel_restaurant_restaurant_id'
    );

    $pntable['pobhotel_hotel_restaurant_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'restaurant_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_restaurant_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_restaurant_column'], 'hotel_restaurant_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_restaurant_column_def']);
    $pntable['pobhotel_hotel_restaurant_column_idx'] = array(
                                          'idx_hotel_restaurant_restaurant_id' => 'restaurant_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_hotel_transportation'] = DBUtil::getLimitedTablename('pobhotel_hotel_transportation');
    $pntable['pobhotel_hotel_transportation_column'] = array(
                                          'id' => 'hotel_transportation_id',
                                          'hotel_id' => 'hotel_transportation_hotel_id',
                                          'transportation_id' => 'hotel_transportation_transportation_id'
    );

    $pntable['pobhotel_hotel_transportation_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'hotel_id' =>'INT(11)',
                                          'transportation_id' =>'INT(11)'
    );
    $pntable['pobhotel_hotel_transportation_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_hotel_transportation_column'], 'hotel_transportation_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_hotel_transportation_column_def']);
    $pntable['pobhotel_hotel_transportation_column_idx'] = array(
                                          'idx_hotel_transportation_hotel_id' => 'hotel_id',
                                          'idx_hotel_transportation_transportation_id' => 'transportation_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_m_room_m_room_format'] = DBUtil::getLimitedTablename('pobhotel_m_room_m_room_format');
    $pntable['pobhotel_m_room_m_room_format_column'] = array(
                                          'id' => 'm_room_m_room_format_id',
                                          'meeting_room_id' => 'm_room_m_room_format_meeting_room_id',
                                          'meeting_room_format_id' => 'm_room_m_room_format_meeting_room_format_id'
    );

    $pntable['pobhotel_m_room_m_room_format_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'meeting_room_id' =>'INT(11)',
                                          'meeting_room_format_id' =>'INT(11)'
    );
    $pntable['pobhotel_m_room_m_room_format_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_m_room_m_room_format_column'], 'm_room_m_room_format_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_m_room_m_room_format_column_def']);
    $pntable['pobhotel_m_room_m_room_format_column_idx'] = array(
                                          'idx_m_room_m_room_format_meeting_room_id' => 'meeting_room_id',
                                          'idx_m_room_m_room_format_meeting_room_format_id' => 'meeting_room_format_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_room_room_amenity'] = DBUtil::getLimitedTablename('pobhotel_room_room_amenity');
    $pntable['pobhotel_room_room_amenity_column'] = array(
                                          'id' => 'room_room_amenity_id',
                                          'room_id' => 'room_room_amenity_room_id',
                                          'room_amenity_id' => 'room_room_amenity_room_amenity_id'
    );

    $pntable['pobhotel_room_room_amenity_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'room_id' =>'INT(11)',
                                          'room_amenity_id' =>'INT(11)'
    );
    $pntable['pobhotel_room_room_amenity_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_room_room_amenity_column'], 'room_room_amenity_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_room_room_amenity_column_def']);
    $pntable['pobhotel_room_room_amenity_column_idx'] = array(
                                          'idx_room_room_amenity_room_id' => 'room_id',
                                          'idx_room_room_amenity_room_amenity_id' => 'room_amenity_id'
    );

////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_r_srvc_r_srvc_detail'] = DBUtil::getLimitedTablename('pobhotel_r_srvc_r_srvc_detail');
    $pntable['pobhotel_r_srvc_r_srvc_detail_column'] = array(
                                          'id' => 'pobhotel_r_srvc_r_srvc_detail_id',
                                          'recreation_srvc_type_id' => 'r_srvc_r_srvc_detail_recreation_srvc_type_id',
                                          'recreation_srvc_detail_id' => 'r_srvc_r_srvc_detail_recreation_srvc_detail_id'
    );
    $pntable['pobhotel_r_srvc_r_srvc_detail_column_def'] = array(
                                          'id' => 'INT(11) INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'recreation_srvc_type_id' =>'INT(11)',
                                          'recreation_srvc_detail_id' =>'INT(11)'
    );
    $pntable['pobhotel_r_srvc_r_srvc_detail_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_r_srvc_r_srvc_detail_column'], 'r_srvc_r_srvc_detail_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_r_srvc_r_srvc_detail_column_def']);
    $pntable['pobhotel_r_srvc_r_srvc_detail_column_idx'] = array(
                                          'idx_r_srvc_r_srvc_detail_recreation_srvc_type_id' => 'recreation_srvc_type_id',
                                          'idx_r_srvc_r_srvc_detail_room_recreation_srvc_detail_id' => 'recreation_srvc_detail_id'
    );


////////////////////////////////////////////////////////////////////////////////////////
    $pntable['pobhotel_room'] = DBUtil::getLimitedTablename('pobhotel_room');
    $pntable['pobhotel_room_column'] = array(
                                          'id' => 'room_id',
                                          'guest_room_type_id' => 'room_guest_room_type_id',
                                          'name' =>'room_name',
                                          'description' => 'room_description',
                                          'images' => 'room_images'
                                          
    );
    $pntable['pobhotel_room_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'guest_room_type_id' =>'INT(11)',
                                          'name' =>'VARCHAR(255)',
                                          'description' =>'VARCHAR(255)',
                                          'images' => 'TEXT  DEFAULT NULL'
    );
    $pntable['pobhotel_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobhotel_room_column'], 'room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobhotel_room_column_def']);
    $pntable['pobhotel_room_column_idx'] = array(
                                          'idx_room_guest_room_type_id' => 'room_guest_room_type_id'
    );


    return $pntable;
  }