<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pntables.php $
 * @version     $Id: pntables.php 529 2010-01-31 01:59:33Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function postcalendar_pntables()
{
    // Initialise table array
    $pntable = array();

    $pc_events = DBUtil::getLimitedTablename('postcalendar_events');
    $pntable['postcalendar_events'] = $pc_events;
    $pntable['postcalendar_events_column'] = array(
        'eid'         => 'pc_eid',              // event ID
        'aid'         => 'pc_aid',              // participant's user ID (default:informant UID)
        'title'       => 'pc_title',            // event title
        'time'        => 'pc_time',             // record timestamp
        'hometext'    => 'pc_hometext',         // event description
        'informant'   => 'pc_informant',        // uid of event submittor
        'eventDate'   => 'pc_eventDate',        // YYYY-MM-DD event start date
        'duration'    => 'pc_duration',         // event duration (in seconds)
        'endDate'     => 'pc_endDate',          // YYYY-MM-DD event end date (optional)
        'recurrtype'  => 'pc_recurrtype',       // type of recurrance (0,1,2)
        'recurrspec'  => 'pc_recurrspec',       // (serialized)
        'startTime'   => 'pc_startTime',        // HH:MM:SS event start time
        'alldayevent' => 'pc_alldayevent',      // bool event all day or not
        'location'    => 'pc_location',         // (serialized) event location
        'conttel'     => 'pc_conttel',          // event contact phone
        'contname'    => 'pc_contname',         // event contact name
        'contemail'   => 'pc_contemail',        // event contact email
        'website'     => 'pc_website',          // event website
        'fee'         => 'pc_fee',              // event fee
        'eventstatus' => 'pc_eventstatus',      // event status (approved, pending)
        'sharing'     => 'pc_sharing',          // event sharing (global, private, etc)
        'hooked_modulename' => 'pc_hooked_modulename', // module name hooked to PC
        'hooked_objectid'   => 'pc_hooked_objectid',   // object id hooked to PC
    );
/**
 * columns removed from previous versions:
 * catid, comments, counter, topic, recurrfreq, endTime, language, meeting_id
 */
    $pntable['postcalendar_events_column_def'] = array(
        'eid'         => 'I(11) UNSIGNED AUTO PRIMARY',      // int(11) unsigned NOT NULL auto_increment
        'aid'         => 'C(30) NOTNULL DEFAULT \'\'',       // varchar(30) NOT NULL default ''
        'title'       => 'C(150) DEFAULT \'\'',              // varchar(150) default ''
        'time'        => 'T',                                // datetime
        'hometext'    => 'X DEFAULT \'\'',                   // text default ''
        'informant'   => 'C(20) NOTNULL DEFAULT \'\'',       // varchar(20) NOT NULL default ''
        'eventDate'   => 'D NOTNULL DEFAULT \'0000-00-00\'', // date NOT NULL default '0000-00-00'
        'duration'    => 'I8(20) NOTNULL DEFAULT 0',         // bigint(20) NOT NULL default 0
        'endDate'     => 'D NOTNULL DEFAULT \'0000-00-00\'', // date NOT NULL default '0000-00-00'
        'recurrtype'  => 'I(1) NOTNULL DEFAULT 0',           // int(1) NOT NULL default 0
        'recurrspec'  => 'X DEFAULT \'\'',                   // text default ''
        'startTime'   => 'C(8) DEFAULT \'00:00:00\'',        // time (MySQL only, so now defined as varchar2)
        'alldayevent' => 'I(1) NOTNULL DEFAULT 0',           // int(1) NOT NULL default 0
        'location'    => 'X',                                // text default ''
        'conttel'     => 'C(50) DEFAULT \'\'',               // varchar(50) default ''
        'contname'    => 'C(50) DEFAULT \'\'',               // varchar(50) default ''
        'contemail'   => 'C(255) DEFAULT \'\'',              // varchar(255) default ''
        'website'     => 'C(255) DEFAULT \'\'',              // varchar(255) default ''
        'fee'         => 'C(50) DEFAULT \'\'',               // varchar(50) default ''
        'eventstatus' => 'I NOTNULL DEFAULT 0',              // int(11) NOT NULL default 0
        'sharing'     => 'I NOTNULL DEFAULT 0',              // int(11) NOT NULL default 0
        'hooked_modulename' => 'C(50) DEFAULT \'\'',         // added version 6.1
        'hooked_objectid'   => 'I(11) DEFAULT 0',            // added version 6.1
    );
    $pntable['postcalendar_events_column_idx'] = array(
        'basic_event' => array(
            'aid',
            'eventDate',
            'endDate',
            'eventstatus',
            'sharing'));
    $pntable['postcalendar_events_db_extra_enable_categorization'] = true;
    $pntable['postcalendar_events_primary_key_column'] = 'eid';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($pntable['postcalendar_events_column'], 'pc_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['postcalendar_events_column_def']);

    // old tables for upgrade/renaming purposes
    $pntable['postcalendar_categories'] = DBUtil::getLimitedTablename('postcalendar_categories');


    ////////////////////////////////////////////////////////////////////////////////////////
    $pntable['postcalendar_room'] = DBUtil::getLimitedTablename('postcalendar_room');
    $pntable['postcalendar_room_column'] = array(
                                          'id' => 'room_id',
                                          'guest_room_type_id' => 'room_guest_room_type_id',
                                          'guest_room_name' => 'room_guest_room_name',
                                          'name' =>'room_name',
                                          'description' => 'room_description'
    );
    $pntable['postcalendar_room_column_def'] = array(
                                          'id' => 'INT(11)  NOTNULL AUTOINCREMENT PRIMARY',
                                          'guest_room_type_id' =>'INT(11)',
                                          'guest_room_name' =>'VARCHAR(255)',
                                          'name' =>'VARCHAR(255)',
                                          'description' =>'VARCHAR(255)'
    );

    $pntable['postcalendar_room_primary_key_column'] = 'id';
    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['postcalendar_room_column'], 'room_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['postcalendar_room_column_def']);
    $pntable['postcalendar_room_column_idx'] = array(
                                          'idx_room_guest_room_type_id' => 'room_guest_room_type_id'
    );



      $pntable['postcalendar_daybooking'] = DBUtil::getLimitedTablename('postcalendar_daybooking');

      $pntable['postcalendar_daybooking_column'] = array(
                                                  'id'                  => 'day_id',
                                                  'cus_id'              => 'day_cus_id',
                                                  'customer_refid'      => 'day_customer_refid',
                                                  'booking_id'          => 'day_booking_id',
                                                  'status_id'           => 'day_status_id',
                                                  'chaincode'           => 'day_chaincode',
                                                  'hotelname'           => 'day_hotelname',
                                                  'isocurrency'         => 'day_isocurrency',
                                                  'date'                => 'day_date',
                                                  'invcode'             => 'day_invcode',
                                                  'rate'                => 'day_rate',
                                                  'identificational'    => 'day_identificational',
                                                  'nameprefix'          => 'day_nameprefix',
                                                  'givenname'           => 'day_givenname',
                                                  'surname'             => 'day_surname',
                                                  'addressline'         => 'day_addressline',
                                                  'cityname'            => 'day_cityname',
                                                  'stateprov'           => 'day_stateprov',
                                                  'countryname'         => 'day_countryname',
                                                  'postalcode'          => 'day_postalcode',
                                                  'mobile'              => 'day_mobile',
                                                  'phone'               => 'day_phone',
                                                  'email'               => 'day_email',
                                                  'addition_request'    => 'day_addition_request',
                                                  'cardcode'            => 'day_cardcode',
                                                  'cardnumber'          => 'day_cardnumber',
                                                  'cardholdername'      => 'day_cardholdernamer',
                                                  'cardexpire'          => 'day_cardexpire',
                                                  'issue_date'          => 'day_issue_date',
                                                  'cardsecurecode'      => 'day_cardsecurecode',
                                                  'cardbankname'        => 'day_cardbankname',
                                                  'cardissuingcountry'  => 'day_cardissuingcountry'
                                          );

      $pntable['postcalendar_daybooking_column_def'] = array(
                                                  'id'               => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                  'cus_id'           => 'INT(11)',
                                                  'customer_refid'   => 'TEXT',
                                                  'booking_id'       => 'VARCHAR(255)',
                                                  'status_id'        => 'INT(2) default 2',
                                                  'chaincode'        => 'VARCHAR(10)',
                                                  'hotelname'        => 'VARCHAR(255)',
                                                  'isocurrency'      => 'VARCHAR(10)',
                                                  'date'             => 'DATE',
                                                  'checkout_date'    => 'DATE',
                                                  'date'             => 'DATE',
                                                  'invcode'          => 'VARCHAR(255)',
                                                  'rate'             => 'DOUBLE',
                                                  'identificational' => 'VARCHAR(50) DEFUALT NULL',
                                                  'nameprefix'       => 'VARCHAR(50) DEFUALT NULL',
                                                  'givenname'        => 'TEXT',
                                                  'surname'          => 'TEXT',
                                                  'addressline'      => 'TEXT',
                                                  'stateprov'        => 'VARCHAR(255) DEFUALT NULL',
                                                  'cityname'         => 'VARCHAR(255) DEFUALT NULL',
                                                  'countryname'      => 'VARCHAR(255) DEFUALT NULL',
                                                  'postalcode'       => 'VARCHAR(50)  DEFUALT NULL',
                                                  'mobile'           => 'VARCHAR(255) DEFUALT NULL',
                                                  'phone'            => 'VARCHAR(255) DEFUALT NULL',
                                                  'email'            => 'TEXT',
                                                  'addition_request' => 'TEXT',
                                                  'cardcode'         => 'VARCHAR(255)',
                                                  'cardnumber'       => 'VARCHAR(255)',
                                                  'cardholdername'   => 'VARCHAR(255)',
                                                  'cardexpire'       => 'VARCHAR(255)',
                                                  'issue_date'       => 'DATETIME',
                                                  'cardsecurecode'   => 'VARCHAR(255)',
                                                  'cardbankname'       => 'VARCHAR(255)',
                                                  'cardissuingcountry'  => 'VARCHAR(255)'
                                                  );
      $pntable['postcalendar_daybooking_primary_key_column'] = 'id';

    return $pntable;
}
