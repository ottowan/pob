<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnuserapi.php $
 * @version     $Id: pnuserapi.php 753 2010-08-08 23:43:08Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

include_once 'modules/PostCalendar/global.php';
include_once 'modules/PostCalendar/pnincludes/DateCalc.class.php';

/**
 * postcalendar_userapi_buildView
 *
 * Builds the calendar display
 * @param string $Date mm/dd/yyyy format (we should use timestamps)
 * @return string generated html output
 * @access public
 */
function postcalendar_userapi_buildView($args)
{
    $dom         = ZLanguage::getModuleDomain('PostCalendar');
    $Date        = $args['Date'];
    $viewtype    = $args['viewtype'];
    $pc_username = $args['pc_username'];
    $filtercats  = $args['filtercats'];
    $func        = $args['func'];

    if (strlen($Date) == 8 && is_numeric($Date)) {
        $Date .= '000000'; // 20060101 + 000000
    }

    // finish setting things up
    $the_year  = substr($Date, 0, 4);
    $the_month = substr($Date, 4, 2);
    $the_day   = substr($Date, 6, 2);
    $last_day  = DateUtil::getDaysInMonth($the_month, $the_year);

    $pc_colclasses = array(
        0 => "pcWeekday", 
        1 => "pcWeekday", 
        2 => "pcWeekday", 
        3 => "pcWeekday", 
        4 => "pcWeekday", 
        5 => "pcWeekday", 
        6 => "pcWeekday");

    // set up some information for later variable creation.
    // This helps establish the correct date ranges for each view.
    // There may be a better way to handle all this.
    switch (_SETTING_FIRST_DAY_WEEK) {
        case _IS_MONDAY:
            $pc_array_pos = 1;
            $first_day = date('w', mktime(0, 0, 0, $the_month, 0, $the_year));
            $week_day = date('w', mktime(0, 0, 0, $the_month, $the_day - 1, $the_year));
            $end_dow = date('w', mktime(0, 0, 0, $the_month, $last_day, $the_year));
            if ($end_dow != 0) {
                $the_last_day = $last_day + (7 - $end_dow);
            } else {
                $the_last_day = $last_day;
            }
            $pc_colclasses[5] = "pcWeekend";
            $pc_colclasses[6] = "pcWeekend";
            break;
        case _IS_SATURDAY:
            $pc_array_pos = 6;
            $first_day = date('w', mktime(0, 0, 0, $the_month, 2, $the_year));
            $week_day = date('w', mktime(0, 0, 0, $the_month, $the_day + 1, $the_year));
            $end_dow = date('w', mktime(0, 0, 0, $the_month, $last_day, $the_year));
            if ($end_dow == 6) {
                $the_last_day = $last_day + 6;
            } elseif ($end_dow != 5) {
                $the_last_day = $last_day + (5 - $end_dow);
            } else {
                $the_last_day = $last_day;
            }
            $pc_colclasses[0] = "pcWeekend";
            $pc_colclasses[1] = "pcWeekend";
            break;
        case _IS_SUNDAY:
        default:
            $pc_array_pos = 0;
            $first_day = date('w', mktime(0, 0, 0, $the_month, 1, $the_year));
            $week_day = date('w', mktime(0, 0, 0, $the_month, $the_day, $the_year));
            $end_dow = date('w', mktime(0, 0, 0, $the_month, $last_day, $the_year));
            if ($end_dow != 6) {
                $the_last_day = $last_day + (6 - $end_dow);
            } else {
                $the_last_day = $last_day;
            }
            $pc_colclasses[0] = "pcWeekend";
            $pc_colclasses[6] = "pcWeekend";
            break;
    }
    // prepare Month Names, Long Day Names and Short Day Names
    $pc_short_day_names = explode(" ", __(/*!First Letter of each Day of week*/'S M T W T F S', $dom));
    $pc_long_day_names  = explode(" ", __('Sunday Monday Tuesday Wednesday Thursday Friday Saturday', $dom));
    // Create an array with the day names in the correct order
    $daynames = array();
    $sdaynames = array();
    for ($i = 0; $i < 7; $i++) {
        if ($pc_array_pos >= 7) {
            $pc_array_pos = 0;
        }
        $daynames[]  = $pc_long_day_names[$pc_array_pos];
        $sdaynames[] = $pc_short_day_names[$pc_array_pos];
        $pc_array_pos++;
    }

    $function_out = array();

    $Date_Calc = new Date_Calc();

    // Setup the starting and ending date ranges for pcGetEvents()
    switch ($viewtype) {
        case 'day':
            $starting_date = date('m/d/Y', mktime(0, 0, 0, $the_month, $the_day, $the_year));
            $ending_date = date('m/d/Y', mktime(0, 0, 0, $the_month, $the_day, $the_year));

            $prev_day = DateUtil::getDatetime_NextDay(-1, '%Y%m%d', $the_year, $the_month, $the_day);
            $next_day = DateUtil::getDatetime_NextDay(1, '%Y%m%d', $the_year, $the_month, $the_day);
            $pc_prev_day = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'day',
                'Date' => $prev_day,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $pc_next_day = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'day',
                'Date' => $next_day,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $function_out['PREV_DAY_URL'] = DataUtil::formatForDisplay($pc_prev_day);
            $function_out['NEXT_DAY_URL'] = DataUtil::formatForDisplay($pc_next_day);
            break;
        case 'week':
            $first_day_of_week = sprintf('%02d', $the_day - $week_day);
            $week_first_day = date('m/d/Y', mktime(0, 0, 0, $the_month, $first_day_of_week, $the_year));
            list ($week_first_day_month, $week_first_day_date, $week_first_day_year) = explode('/', $week_first_day);
            $week_last_day = date('m/d/Y', mktime(0, 0, 0, $the_month, $first_day_of_week + 6, $the_year));
            list ($week_last_day_month, $week_last_day_date, $week_last_day_year) = explode('/', $week_last_day);

            $starting_date = "$week_first_day_month/$week_first_day_date/$week_first_day_year";
            $ending_date = "$week_last_day_month/$week_last_day_date/$week_last_day_year";
            $calendarView = $Date_Calc->getCalendarWeek($week_first_day_date, $week_first_day_month, $week_first_day_year, '%Y-%m-%d');

            $prev_week = date('Ymd', mktime(0, 0, 0, $week_first_day_month, $week_first_day_date - 7, $week_first_day_year));
            $next_week = date('Ymd', mktime(0, 0, 0, $week_last_day_month, $week_last_day_date + 1, $week_last_day_year));
            $pc_prev_week = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'week',
                'Date' => $prev_week,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $pc_next_week = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'week',
                'Date' => $next_week,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $function_out['PREV_WEEK_URL'] = DataUtil::formatForDisplay($pc_prev_week);
            $function_out['NEXT_WEEK_URL'] = DataUtil::formatForDisplay($pc_next_week);
            break;
        case 'month':
            $starting_date = date('m/d/Y', mktime(0, 0, 0, $the_month, 1 - $first_day, $the_year));
            $ending_date = date('m/d/Y', mktime(0, 0, 0, $the_month, $the_last_day, $the_year));
            $calendarView = $Date_Calc->getCalendarMonth($the_month, $the_year, '%Y-%m-%d');

            $prev_month = DateUtil::getDatetime_NextMonth(-1, '%Y%m%d', $the_year, $the_month, 1);
            $next_month = DateUtil::getDatetime_NextMonth(1, '%Y%m%d', $the_year, $the_month, 1);
            $pc_prev_month = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => $viewtype,
                'Date' => $prev_month,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $pc_next_month = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => $viewtype,
                'Date' => $next_month,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $function_out['PREV_MONTH_URL']   = DataUtil::formatForDisplay($pc_prev_month);
            $function_out['NEXT_MONTH_URL']   = DataUtil::formatForDisplay($pc_next_month);
            $function_out['S_LONG_DAY_NAMES'] = $daynames;
            break;
        case 'year':
            $starting_date = date('m/d/Y', mktime(0, 0, 0, 1, 1, $the_year));
            $ending_date = date('m/d/Y', mktime(0, 0, 0, 1, 1, $the_year + 1));
            $calendarView = $Date_Calc->getCalendarYear($the_year, '%Y-%m-%d');

            $prev_year = date('Ymd', mktime(0, 0, 0, 1, 1, $the_year - 1));
            $next_year = date('Ymd', mktime(0, 0, 0, 1, 1, $the_year + 1));
            $pc_prev_year = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'year',
                'Date' => $prev_year,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $pc_next_year = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'year',
                'Date' => $next_year,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $function_out['PREV_YEAR_URL']      = DataUtil::formatForDisplay($pc_prev_year);
            $function_out['NEXT_YEAR_URL']      = DataUtil::formatForDisplay($pc_next_year);
            $function_out['A_MONTH_NAMES']      = explode(" ", __('January February March April May June July August September October November December', $dom));
            $function_out['S_SHORT_DAY_NAMES']  = $sdaynames;
            break;
        case 'xml':
        case 'list':
            $listmonths    = pnModGetVar('PostCalendar', 'pcListMonths');
            $listyears     = floor($listmonths/12);
            $listendyears  = (int) $the_year + (int) $listyears;
            $listmonths    = $listmonths % 12;
            $listendmonths = (int) $the_month + (int) $listmonths;
            if ($listendmonths > 12) {
                $listendyears++;
                $listendmonths = $listendmonths - 12;
            }
            $starting_date = "$the_month/$the_day/$the_year";
            $ending_date   = "$listendmonths/$the_day/$listendyears";

            $prev_list = date('Ymd', mktime(0, 0, 0, $the_month - $listmonths, $the_day, $the_year));
            $next_list = date('Ymd', mktime(0, 0, 0, $listendmonths, $the_day, $listendyears));
            $pc_prev_list = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'list',
                'Date' => $prev_list,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $pc_next_list = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'list',
                'Date' => $next_list,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats));
            $function_out['PREV_LIST_URL'] = DataUtil::formatForDisplay($pc_prev_list);
            $function_out['NEXT_LIST_URL'] = DataUtil::formatForDisplay($pc_next_list);
            break;
    }

    // Load the events
    $eventsByDate = & pnModAPIFunc('PostCalendar', 'event', 'getEvents', array(
        'start' => $starting_date,
        'end' => $ending_date,
        'filtercats' => $filtercats,
        'Date' => $Date,
        'pc_username' => $pc_username));

    if (isset($calendarView)) {
        $function_out['CAL_FORMAT'] = $calendarView;
    }
    // convert categories array to proper filter info
    $selectedcategories = array();
    if (is_array($filtercats)) {
        $catsarray = $filtercats['__CATEGORIES__'];
        foreach ($catsarray as $propname => $propid) {
            if ($propid > 0) {
                $selectedcategories[$propname] = $propid; // removes categories set to 'all'
            }
        }
    }

    $function_out['FUNCTION']           = $func;
    $function_out['VIEW_TYPE']          = $viewtype;
    $function_out['A_EVENTS']           = $eventsByDate;
    $function_out['selectedcategories'] = $selectedcategories;
    $function_out['MONTH_START_DATE']   = date('Y-m-d', mktime(0, 0, 0, $the_month, 1, $the_year));
    $function_out['MONTH_END_DATE']     = date('Y-m-t', mktime(0, 0, 0, $the_month, 1, $the_year));
    $function_out['TODAY_DATE']         = DateUtil::getDatetime('', '%Y-%m-%d');
    $function_out['DATE']               = $Date;
    $function_out['pc_colclasses']      = $pc_colclasses;

    return $function_out;
}

/**
 * postcalendar_userapi_getDate
 *
 * get the correct day, format it and return
 * @param string format
 * @param string Date
 * @param string jumpday
 * @param string jumpmonth
 * @param string jumpyear
 * @return string formatted date string
 * @access public
 */
function postcalendar_userapi_getDate($args)
{
    $format = (!empty($args['format'])) ? $args['format'] : '%Y%m%d%H%M%S';

    $time      = time();
    $jumpday   = isset($args['jumpday']) ? $args['jumpday'] : strftime('%d', $time);
    $jumpmonth = isset($args['jumpmonth']) ? $args['jumpmonth'] : strftime('%m', $time);
    $jumpyear  = isset($args['jumpyear']) ? $args['jumpyear'] : strftime('%Y', $time);

    if (pnUserLoggedIn()) {
        $time += (pnUserGetVar('timezone_offset') - pnConfigGetVar('timezone_offset')) * 3600;
    }

    $Date = isset($args['Date']) ? $args['Date'] : '';
    if (empty($Date)) {
        // if we still don't have a date then calculate it
        $Date = (int) "$jumpyear$jumpmonth$jumpday";
    }

    $y = substr($Date, 0, 4);
    $m = substr($Date, 4, 2);
    $d = substr($Date, 6, 2);
    return DateUtil::strftime($format, mktime(0, 0, 0, $m, $d, $y));
}


//////////////////////////////////////////////////////////////////
//
//    $args = array(
//                  "id" => "001",
//                  "name" => "101",
//                  "event" => "insert",
//    );
//    pnModAPIFunc('PostCalendar', 'user', 'insertRoom', $args);
//
//////////////////////////////////////////////////////////////////
function PostCalendar_userapi_insertRoom($args) {


  if($args["id"] && $args["name"] && $args["event"] && (trim($args["event"]) == "insert")){

    //Insert statement
    $obj = array(
                 'id'    => $args["id"],
                 'guest_room_type_id' => $args["guest_room_type_id"],
                 'guest_room_name' => $args["guest_room_name"],
                 'name' => $args["name"],
                 'description' => $args["description"]
           );

    // do the insert
    DBUtil::insertObject($obj, 'postcalendar_room');
    return true;

  }else if($args["id"] && $args["name"] && $args["event"] && (trim($args["event"]) == "update")){

    //Update statement
    $obj = array('name' => $args["name"]);
    $where    = "WHERE $tableMember.$columnMember[uid]=".$uid;
    DBUtil::updateObject ($obj, 'postcalendar_room', $where);
    return true;

  }else if($args["id"] && $args["name"] && $args["event"] && (trim($args["event"]) == "delete")){

    //Delete statement
    $where    = "WHERE $tableMember.$columnMember[uid]=".$uid;
    DBUtil::deleteObjectByID($postcalendar_room, $args["id"]);
    return true;
  }else{
    return false;
  }


  return true;
}

/////////////////////////////
//
//  $args = array("hotelcode"=>"POBHT000005");
//  pnModAPIFunc('PostCalendar', 'user', 'insertBooking', $args);
//
////////////////////////////
function PostCalendar_userapi_insertBooking($args) {

  print_r($args);
/*
  if($args["id"]){
    //Insert statement
    $obj = array(
                 'id'                 => $args["id"],
                 'cus_id'             => $args["cus_id"],
                 'customer_refid'     => $args["customer_refid"],
                 'booking_id'         => $args["booking_id"],
                 'status_id'          => $args["status_id"],
                 'chaincode'          => $args["chaincode"],
                 'hotelname'          => $args["hotelname"],
                 'isocurrency'        => $args["isocurrency"],
                 'date'               => $args["date"],
                 'invcode'            => $args["invcode"],
                 'rate'               => $args["rate"],
                 'identificational'   => $args["identificational"],
                 'nameprefix'         => $args["nameprefix"],
                 'givenname'          => $args["givenname"],
                 'surname'            => $args["surname"],
                 'addressline'        => $args["addressline"],
                 'cityname'           => $args["cityname"],
                 'stateprov'          => $args["stateprov"],
                 'countryname'        => $args["countryname"],
                 'postalcode'         => $args["postalcode"],
                 'mobile'             => $args["mobile"],
                 'phone'              => $args["phone"],
                 'email'              => $args["email"],
                 'addition_request'   => $args["addition_request"],
                 'cardcode'           => $args["cardcode"],
                 'cardnumber'         => $args["cardnumber"],
                 'cardholdername'     => $args["cardholdername"],
                 'cardexpire'         => $args["cardexpire"],
                 'issue_date'         => $args["issue_date"],
                 'cardsecurecode'     => $args["cardsecurecode"],
                 'cardbankname'       => $args["cardbankname"],
                 'cardissuingcountry' => $args["cardissuingcountry"]
           );

    // do the insert
    DBUtil::insertObject($obj, 'postcalendar_daybooking');





  `pc_eid` int(11) unsigned NOT NULL auto_increment,
  `pc_aid` varchar(30) NOT NULL default '',
  `pc_title` varchar(150) default '',
  `pc_time` datetime default NULL,
  `pc_hometext` text,
  `pc_informant` varchar(20) NOT NULL default '',
  `pc_eventDate` date NOT NULL default '0000-00-00',
  `pc_duration` bigint(20) NOT NULL default '0',
  `pc_endDate` date NOT NULL default '0000-00-00',
  `pc_recurrtype` int(1) NOT NULL default '0',
  `pc_recurrspec` text,
  `pc_startTime` varchar(8) default '00:00:00',
  `pc_alldayevent` int(1) NOT NULL default '0',
  `pc_location` text,
  `pc_conttel` varchar(50) default '',
  `pc_contname` varchar(50) default '',
  `pc_contemail` varchar(255) default '',
  `pc_website` varchar(255) default '',
  `pc_fee` varchar(50) default '',
  `pc_eventstatus` int(11) NOT NULL default '0',
  `pc_sharing` int(11) NOT NULL default '0',
  `pc_hooked_modulename` varchar(50) default '',
  `pc_hooked_objectid` int(11) default '0',
  `pc_obj_status` varchar(1) NOT NULL default 'A',
  `pc_cr_date` datetime NOT NULL default '1970-01-01 00:00:00',
  `pc_cr_uid` int(11) NOT NULL default '0',
  `pc_lu_date` datetime NOT NULL default '1970-01-01 00:00:00',
  `pc_lu_uid` int(11) NOT NULL default '0',


    $dom = ZLanguage::getModuleDomain('PostCalendar');

    Loader::loadClass('CategoryUtil');
    $cat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/PostCalendar/Events');

    $event = array(
        'title'          => __('PostCalendar Installed', $dom),
        'hometext'       => __(':text:On this date, the PostCalendar module was installed. Thank you for trying PostCalendar! This event can be safely deleted if you wish.', $dom),
        'aid'            => SessionUtil::getVar('uid'),
        'time'           => date("Y-m-d H:i:s"),
        'informant'      => SessionUtil::getVar('uid'),
        'eventDate'      => date('Y-m-d'),
        'duration'       => 3600,
        'recurrtype'     => 0,  //norepeat
        'recurrspec'     => 'a:5:{s:17:"event_repeat_freq";s:0:"";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:0:"";}',
        'startTime'      => '01:00:00',
        'alldayevent'    => 1,
        'location'       => 'a:6:{s:14:"event_location";s:0:"";s:13:"event_street1";s:0:"";s:13:"event_street2";s:0:"";s:10:"event_city";s:0:"";s:11:"event_state";s:0:"";s:12:"event_postal";s:0:"";}',
        'eventstatus'    => 1,  // approved
        'sharing'        => 3,  // global
        'website'        => 'http://code.zikula.org/soundwebdevelopment/wiki/PostCalendar',
        '__CATEGORIES__' => array(
            'Main' => $cat['id']),
        '__META__'       => array(
            'module' => 'PostCalendar'));

    if (DBUtil::insertObject($event, 'postcalendar_events', 'eid')) {
        LogUtil::registerStatus(__("PostCalendar: Installation event created.", $dom));
        return true;
    }

    return LogUtil::registerError(__('Error! Could not create an installation event.', $dom));


    return true;
  } else{
    return false;
  }
*/


}