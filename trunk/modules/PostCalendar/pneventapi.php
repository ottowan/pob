<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pneventapi.php $
 * @version     $Id: pneventapi.php 753 2010-08-08 23:43:08Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

include_once 'modules/PostCalendar/global.php';
include_once 'modules/PostCalendar/pnincludes/DateCalc.class.php';

/**
 * This is the event handler api
 **/

/**
 * @description Internal callback class used to check permissions to each item
 *              borrowed from the News module
 * @author      Jorn Wildt
 */
class postcalendar_eventapi_result_checker
{
    var $enablecategorization;

    function postcalendar_eventapi_result_checker()
    {
        $this->enablecategorization = pnModGetVar('PostCalendar', 'enablecategorization');
    }

    // This method is called by DBUtil::selectObjectArrayFilter() for each and every search result.
    // A return value of true means "keep result" - false means "discard".
    function checkResult(&$item)
    {
        $ok = SecurityUtil::checkPermission('PostCalendar::Event', "$item[title]::$item[eid]", ACCESS_OVERVIEW); 

        if ($this->enablecategorization)
        {
            ObjectUtil::expandObjectWithCategories($item, 'postcalendar_events', 'eid');
            $ok = $ok && CategoryUtil::hasCategoryAccess($item['__CATEGORIES__'],'PostCalendar');
        }

        return $ok;
    }
}

/**
 * postcalendar_eventapi_queryEvents //new name
 * Returns an array containing the event's information (plural or singular?)
 * @param array $args arguments. Expected keys:
 *              eventstatus: -1 == hidden ; 0 == queued ; 1 == approved (default)
 *              start: Events start date (default today)
 *              end: Events end_date (default 0000-00-00)
 *              s_keywords: search info
 *              filtercats: categories to query events from
 *              pc_username: event type or user id
 * @return array The events
 */
function postcalendar_eventapi_queryEvents($args)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    $start       = $args['start'];
    $end         = $args['end'] ? $args['end'] : '0000-00-00';
    $s_keywords  = $args['s_keywords'];
    $filtercats  = $args['filtercats'];
    $pc_username = $args['pc_username'];
    $eventstatus = isset($args['eventstatus']) ? $args['eventstatus'] : 1;

    if (_SETTING_ALLOW_USER_CAL) {
        $filterdefault = _PC_FILTER_ALL;
    } else {
        $filterdefault = _PC_FILTER_GLOBAL;
    }
    if (empty($pc_username)) {
        $pc_username = $filterdefault;
    }
    if (!pnUserLoggedIn()) {
        $pc_username = _PC_FILTER_GLOBAL;
    }

    $userid = pnUserGetVar('uid');

    // convert $pc_username to useable information
    /* possible values:
    _PC_FILTER_GLOBAL (-1)  = all public events
    _PC_FILTER_ALL (-2)     = all public events + my events
    _PC_FILTER_PRIVATE (-3) = just my private events
    */
    if ($pc_username > 0) {
        // possible values: a user id - only an admin can use this
        $ruserid = $pc_username; // keep the id
        $pc_username = _PC_FILTER_PRIVATE;
    } else {
        $ruserid = $userid; // use current user's ID
    }

    if (!isset($eventstatus) || ((int) $eventstatus < -1 || (int) $eventstatus > 1)) {
        $eventstatus = 1;
    }

    if (!isset($start)) {
        $start = DateUtil::getDatetime(null, '%Y-%m-%d');
    }

    list ($startyear, $startmonth, $startday) = explode('-', $start);

    $where = "WHERE pc_eventstatus=$eventstatus
              AND (pc_endDate>='$start'
              OR (pc_endDate='0000-00-00' AND pc_recurrtype<>'0')
              OR pc_eventDate>='$start')
              AND pc_eventDate<='$end' ";

    // filter event display based on selection
    /* possible event sharing values @v5.8
    'SHARING_PRIVATE',       0);
    'SHARING_PUBLIC',        1); //remove in v6.0 - convert to SHARING_GLOBAL
    'SHARING_BUSY',          2); //remove in v6.0 - convert to SHARING_PRIVATE
    'SHARING_GLOBAL',        3);
    'SHARING_HIDEDESC',      4); //remove in v6.0 - convert to SHARING_PRIVATE
    */
    switch ($pc_username) {
        case _PC_FILTER_PRIVATE: // show just private events
            $where .= "AND pc_aid = $ruserid ";
            $where .= "AND (pc_sharing = '" . SHARING_PRIVATE . "' ";
            $where .= "OR pc_sharing = '" . SHARING_BUSY . "' "; //deprecated
            $where .= "OR pc_sharing = '" . SHARING_HIDEDESC . "') "; //deprecated
            break;
        case _PC_FILTER_ALL: // show all public/global AND private events
            $where .= "AND (pc_aid = $ruserid ";
            $where .= "AND (pc_sharing = '" . SHARING_PRIVATE . "' ";
            $where .= "OR pc_sharing = '" . SHARING_BUSY . "' "; //deprecated
            $where .= "OR pc_sharing = '" . SHARING_HIDEDESC . "') "; //deprecated
            $where .= "OR (pc_sharing = '" . SHARING_GLOBAL . "' ";
            $where .= "OR pc_sharing = '" . SHARING_PUBLIC . "')) "; //deprecated
            break;
        case _PC_FILTER_GLOBAL: // show all public/global events
        default:
            $where .= "AND (pc_sharing = '" . SHARING_GLOBAL . "' ";
            $where .= "OR pc_sharing = '" . SHARING_PUBLIC . "') "; //deprecated
    }

    // convert categories array to proper filter info
    if (is_array($filtercats)) {
        $catsarray = $filtercats['__CATEGORIES__'];
        foreach ($catsarray as $propname => $propid) {
            if (is_array($propid)) { // select multiple used
                foreach ($propid as $int_key => $int_id) {
                    if ($int_id <= 0) {
                        unset($catsarray[$propname][$int_key]); // removes categories set to 'all' (0)
                    }
                    if (empty($catsarray[$propname])) {
                        unset($catsarray[$propname]);
                    }
                }
            } elseif (strstr($propid, ',')) { // category zLP_multiselctor used
                $catsarray[$propname] = explode(',', $propid);
                // no propid should be '0' in this case
            } else { // single selectbox used
                if ($propid <= 0) {
                    unset($catsarray[$propname]); // removes categories set to 'all' (0)
                }
            }
        }
        if (!empty($catsarray)) {
            $catsarray['__META__']['module'] = "PostCalendar"; // required for search operation
        }
    } else {
        $catsarray = array();
    }
    if (!empty($s_keywords)) {
        $where .= "AND $s_keywords";
    }

    $permChecker = new postcalendar_eventapi_result_checker();
    $events = DBUtil::selectObjectArrayFilter('postcalendar_events', $where, null, null, null, null, $permChecker, $catsarray);

    return $events;
}

/**
 * This function returns an array of events sorted by date
 * expected args (from postcalendar_userapi_buildView): start, end
 *    if either is present, both must be present. else uses today's/jumped date.
 * expected args (from search/postcalendar_search_options): s_keywords, filtercats, seachstart, searchend
 **/
function postcalendar_eventapi_getEvents($args)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    $start       = isset($args['start'])       ? $args['start']       : '';
    $end         = isset($args['end'])         ? $args['end']         : '';
    $s_keywords  = isset($args['s_keywords'])  ? $args['s_keywords']  : ''; // search WHERE string
    $filtercats  = isset($args['filtercats'])  ? $args['filtercats']  : '';
    $pc_username = isset($args['pc_username']) ? $args['pc_username'] : '';
    $searchstart = isset($args['searchstart']) ? $args['searchstart'] : '';
    $searchend   = isset($args['searchend'])   ? $args['searchend']   : '';
    $Date        = isset($args['Date'])        ? $args['Date']        : '';
    $sort        = ((isset($args['sort'])) && ($args['sort'] == 'DESC')) ? 'DESC' : 'ASC';

    $date = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
        'Date' => $Date)); //formats date


    if (!empty($s_keywords)) {
        unset($start);
        unset($end);
    } // clear start and end dates for search

    // update news-hooked stories that have been published since last pageload
    if (pnModIsHooked('postcalendar', 'news')) {
        pnModAPIFunc('PostCalendar', 'hooks', 'scheduler');
    }

    $currentyear  = substr($date, 0, 4);
    $currentmonth = substr($date, 4, 2);
    $currentday   = substr($date, 6, 2);

    $Date_Calc = new Date_Calc();

    if (isset($start) && isset($end)) {
        list ($startmonth, $startday, $startyear) = explode('/', $start);
        list ($endmonth, $endday, $endyear) = explode('/', $end);

        $s = (int) "$startyear$startmonth$startday";
        if ($s > $date) {
            $currentyear = $startyear;
            $currentmonth = $startmonth;
            $currentday = $startday;
        }
        $start_date = $Date_Calc->dateFormat($startday, $startmonth, $startyear, '%Y-%m-%d');
        $end_date = $Date_Calc->dateFormat($endday, $endmonth, $endyear, '%Y-%m-%d');
    } else {
        $startmonth = $endmonth = $currentmonth;
        $startday = $endday = $currentday;
        $startyear = $currentyear + $searchstart;
        $endyear = $currentyear + $searchend;
        $start_date = $startyear . '-' . $startmonth . '-' . $startday;
        $end_date = $endyear . '-' . $endmonth . '-' . $endday;
    }

    if (!isset($s_keywords)) {
        $s_keywords = '';
    }
    $events = pnModAPIFunc('PostCalendar', 'event', 'queryEvents', array(
        'start'       => $start_date,
        'end'         => $end_date,
        's_keywords'  => $s_keywords,
        'filtercats'  => $filtercats,
        'pc_username' => $pc_username));

    //==============================================================
    // Here an array is built consisting of the date ranges
    // specific to the current view.  This array is then
    // used to build the calendar display.
    //==============================================================
    $days = array();
    $sday = $Date_Calc->dateToDays($startday, $startmonth, $startyear);
    $eday = $Date_Calc->dateToDays($endday, $endmonth, $endyear);
    if ($sort == 'DESC') { // format days array in date-descending order
        for ($cday = $eday; $cday >= $sday; $cday--) {
            $d = $Date_Calc->daysToDate($cday, '%d');
            $m = $Date_Calc->daysToDate($cday, '%m');
            $y = $Date_Calc->daysToDate($cday, '%Y');
            $store_date = $Date_Calc->dateFormat($d, $m, $y, '%Y-%m-%d');
            $days[$store_date] = array();
        }
    } else { // format days array in date-ascending order
        for ($cday = $sday; $cday <= $eday; $cday++) {
            $d = $Date_Calc->daysToDate($cday, '%d');
            $m = $Date_Calc->daysToDate($cday, '%m');
            $y = $Date_Calc->daysToDate($cday, '%Y');
            $store_date = $Date_Calc->dateFormat($d, $m, $y, '%Y-%m-%d');
            $days[$store_date] = array();
        }
    }

    foreach ($events as $event) {
        $event = pnModAPIFunc('PostCalendar', 'event', 'formateventarrayfordisplay', $event);

        list ($eventstartyear, $eventstartmonth, $eventstartday) = explode('-', $event['eventDate']);

        // determine the stop date for this event
        $stop = ($event['endDate'] == '0000-00-00') ? $end_date : $event['endDate'];

        // this switch block fills the $days array with events. It computes recurring events and adds the recurrances to the $days array also
        switch ($event['recurrtype']) {
            // Events that do not repeat only have a startday (eventDate)
            case NO_REPEAT:
                if (isset($days[$event['eventDate']])) {
                    $days[$event['eventDate']][] = $event;
                }
                break;
            case REPEAT:
                $rfreq = $event['repeat']['event_repeat_freq']; // could be any int
                $rtype = $event['repeat']['event_repeat_freq_type']; // REPEAT_EVERY_DAY (0), REPEAT_EVERY_WEEK (1), REPEAT_EVERY_MONTH (2), REPEAT_EVERY_YEAR (3)
                // we should bring the event up to date to make this a tad bit faster
                // any ideas on how to do that, exactly??? dateToDays probably. (RNG <5.0)
                $newyear = $eventstartyear;
                $newmonth = $eventstartmonth;
                $newday = $eventstartday;
                $occurance = $Date_Calc->dateFormat($newday, $newmonth, $newyear, '%Y-%m-%d');
                while ($occurance < $start_date) {
                    $occurance = postcalendar_eventapi_dateIncrement(array(
                        'd' => $newday, 
                        'm' => $newmonth, 
                        'y' => $newyear, 
                        'f' => $rfreq, 
                        't' => $rtype));
                    list ($newyear, $newmonth, $newday) = explode('-', $occurance);
                }
                while ($occurance <= $stop) {
                    if (isset($days[$occurance])) {
                        $days[$occurance][] = $event;
                    }
                    $occurance = postcalendar_eventapi_dateIncrement(array(
                        'd' => $newday, 
                        'm' => $newmonth, 
                        'y' => $newyear, 
                        'f' => $rfreq, 
                        't' => $rtype));
                    list ($newyear, $newmonth, $newday) = explode('-', $occurance);
                }
                break;
            case REPEAT_ON:
                $rfreq = $event['repeat']['event_repeat_on_freq']; // could be any int
                $rnum = $event['repeat']['event_repeat_on_num']; // REPEAT_ON_1ST (1), REPEAT_ON_2ND (2), REPEAT_ON_3RD (3), REPEAT_ON_4TH (4), REPEAT_ON_LAST(5)
                $rday = $event['repeat']['event_repeat_on_day']; // REPEAT_ON_SUN (0), REPEAT_ON_MON (1), REPEAT_ON_TUE (2), REPEAT_ON_WED (3), REPEAT_ON_THU(4), REPEAT_ON_FRI (5), REPEAT_ON_SAT (6)
                $newmonth = $eventstartmonth;
                $newyear = $eventstartyear;
                $newday = $eventstartday;
                // make us current
                while ($newyear < $currentyear) {
                    $occurance = date('Y-m-d', mktime(0, 0, 0, $newmonth + $rfreq, $newday, $newyear));
                    list ($newyear, $newmonth, $newday) = explode('-', $occurance);
                }
                // populate the event array
                while ($newyear <= $endyear) { // was $currentyear
                    $dnum = $rnum; // get day event repeats on
                    do {
                        $occurance = $Date_Calc->NWeekdayOfMonth($dnum--, $rday, $newmonth, $newyear, "%Y-%m-%d");
                    } while ($occurance === -1);
                    if (isset($days[$occurance]) && $occurance <= $stop) {
                        $days[$occurance][] = $event;
                    }
                    $occurance = date('Y-m-d', mktime(0, 0, 0, $newmonth + $rfreq, $newday, $newyear));
                    list ($newyear, $newmonth, $newday) = explode('-', $occurance);
                }
                break;
        } // <- end of switch($event['recurrtype'])
    } // <- end of foreach($events as $event)
    return $days;
}

/**
 * postcalendar_eventapi_writeEvent()
 * write an event to the DB
 * @param $args array of event data
 * @return bool true on success : false on failure;
 */
function postcalendar_eventapi_writeEvent($args)
{
    $eventdata = $args['eventdata'];
    if (!isset($eventdata['is_update'])) {
        $eventdata['is_update'] = false;
    }

    if ($eventdata['is_update']) {
        unset($eventdata['is_update']);
        $obj = array(
            $eventdata['eid'] => $eventdata);
        $result = DBUtil::updateObjectArray($obj, 'postcalendar_events', 'eid');
        pnModCallHooks('item', 'update', $eventdata['eid'], array(
            'module' => 'PostCalendar'));
    } else { //new event
        unset($eventdata['eid']); //be sure that eid is not set on insert op to autoincrement value
        unset($eventdata['is_update']);
        $eventdata['time'] = date("Y-m-d H:i:s"); //current date for timestamp on event
        $result = DBUtil::insertObject($eventdata, 'postcalendar_events', 'eid');
        pnModCallHooks('item', 'create', $result['eid'], array(
            'module' => 'PostCalendar'));
    }
    if ($result === false) {
        return false;
    }

    return $result['eid'];
}

/**
 * postcalendar_eventapi_buildSubmitForm()
 * generate information to help build the submit form
 * this is also used on a preview of event function, so $eventdata is passed from that if 'loaded'
 * args: 'eventdata','Date'
 * @return $form_data (array) key, val pairs to be assigned to the template, including default event data
 */
function postcalendar_eventapi_buildSubmitForm($args)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    $eventdata = $args['eventdata']; // contains data for editing if loaded

    // get event default values
    $eventDefaults = pnModGetVar('PostCalendar', 'pcEventDefaults');

    // format date information
    if ((!isset($eventdata['endDate'])) || ($eventdata['endDate'] == '') || ($eventdata['endDate'] == '00000000') || ($eventdata['endDate'] == '0000-00-00')) {
        $eventdata['endvalue'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => $args['Date'],
            'format' => _SETTING_DATE_FORMAT));
        $eventdata['endDate'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => $args['Date'],
            'format' => __(/*!ensure translation EXACTLY the same as locale definition*/'%Y-%m-%d'))); // format for JS cal - intentional use of core domain
    } else {
        $eventdata['endvalue'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => str_replace('-', '', $eventdata['endDate']),
            'format' => _SETTING_DATE_FORMAT));
        $eventdata['endDate'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => str_replace('-', '', $eventdata['endDate']),
            'format' => __(/*!ensure translation EXACTLY the same as locale definition*/'%Y-%m-%d'))); // format for JS cal - intentional use of core domain
    }
    if ((!isset($eventdata['eventDate'])) || ($eventdata['eventDate'] == '')) {
        $eventdata['eventDatevalue'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => $args['Date'],
            'format' => _SETTING_DATE_FORMAT));
        $eventdata['eventDate'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => $args['Date'],
            'format' => __(/*!ensure translation EXACTLY the same as locale definition*/'%Y-%m-%d'))); // format for JS cal - intentional use of core domain
    } else {
        $eventdata['eventDatevalue'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => str_replace('-', '', $eventdata['eventDate']),
            'format' => _SETTING_DATE_FORMAT));
        $eventdata['eventDate'] = pnModAPIFunc('PostCalendar', 'user', 'getDate', array(
            'Date' => str_replace('-', '', $eventdata['eventDate']),
            'format' => __(/*!ensure translation EXACTLY the same as locale definition*/'%Y-%m-%d'))); // format for JS cal - intentional use of core domain
    }

    if ((SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) && (_SETTING_ALLOW_USER_CAL)) {
        $users = DBUtil::selectFieldArray('users', 'uname', null, null, null, 'uid');
        $form_data['users'] = $users;
    }
    $eventdata['aid'] = isset($eventdata['aid']) ? $eventdata['aid'] : pnUserGetVar('uid'); // set value of user-select box
    $form_data['username_selected'] = pnUsergetVar('uname', $eventdata['aid']); // for display of username

    // load the category registry util
    if (!Loader::loadClass('CategoryRegistryUtil')) {
        pn_exit(__f('Error! Unable to load class [%s]', 'CategoryRegistryUtil'));
    }
    $form_data['catregistry'] = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
    $form_data['cat_count'] = count($form_data['catregistry']);
    // configure default categories
    $eventdata['__CATEGORIES__'] = isset($eventdata['__CATEGORIES__']) ? $eventdata['__CATEGORIES__'] : $eventDefaults['categories'];

    // All-day event values for radio buttons
    $eventdata['alldayevent'] = isset($eventdata['alldayevent']) ? $eventdata['alldayevent'] : $eventDefaults['alldayevent'];
    $form_data['Selected'] = postcalendar_eventapi_alldayselect($eventdata['alldayevent']);

    $form_data['minute_interval'] = _SETTING_TIME_INCREMENT;

    $eventdata['endTime'] = (empty($eventdata['endTime'])) ? pnModApiFunc('PostCalendar', 'event', 'computeendtime', $eventDefaults) : $eventdata['endTime'];

    $eventdata['startTime'] = (empty($eventdata['startTime'])) ? $eventDefaults['startTime'] : $eventdata['startTime'];

    // hometext
    if (empty($eventdata['HTMLorTextVal'])) {
        $eventdata['HTMLorTextVal'] = 'text'; // default to text
    }

    // create html/text selectbox
    $form_data['EventHTMLorText'] = array(
        'text' => __('Plain text', $dom),
        'html' => __('HTML-formatted', $dom));

    // sharing selectbox
    $form_data['sharingselect'] = pnModApiFunc('PostCalendar', 'event', 'sharingselect');

    if (!isset($eventdata['sharing'])) {
        $eventdata['sharing'] = $eventDefaults['sharing'];
    }

    // recur type radio selects
    $form_data['SelectedNoRepeat'] = ((!isset($eventdata['recurrtype'])) || ((int) $eventdata['recurrtype'] == 0)) ? " checked='checked'" : ''; //default
    $form_data['SelectedRepeat']   = ((isset($eventdata['recurrtype']))  && ((int) $eventdata['recurrtype'] == 1)) ? " checked='checked'" : '';
    $form_data['SelectedRepeatOn'] = ((isset($eventdata['recurrtype']))  && ((int) $eventdata['recurrtype'] == 2)) ? " checked='checked'" : '';

    // recur select box arrays
    $in = explode("/", __('Day(s)/Week(s)/Month(s)/Year(s)', $dom));
    $keys = array(
        REPEAT_EVERY_DAY,
        REPEAT_EVERY_WEEK,
        REPEAT_EVERY_MONTH,
        REPEAT_EVERY_YEAR);
    $selectarray = array_combine($keys, $in);
    $form_data['repeat_freq_type'] = $selectarray;

    $in = explode("/", __('First/Second/Third/Fourth/Last', $dom));
    $keys = array(
        REPEAT_ON_1ST,
        REPEAT_ON_2ND,
        REPEAT_ON_3RD,
        REPEAT_ON_4TH,
        REPEAT_ON_LAST);
    $selectarray = array_combine($keys, $in);
    $form_data['repeat_on_num'] = $selectarray;

    $in = explode(" ", __('Sun Mon Tue Wed Thu Fri Sat', $dom));
    $keys = array(
        REPEAT_ON_SUN,
        REPEAT_ON_MON,
        REPEAT_ON_TUE,
        REPEAT_ON_WED,
        REPEAT_ON_THU,
        REPEAT_ON_FRI,
        REPEAT_ON_SAT);
    $selectarray = array_combine($keys, $in);
    $form_data['repeat_on_day'] = $selectarray;

    // recur defaults
    if (empty($eventdata['repeat']['event_repeat_freq_type']) || $eventdata['repeat']['event_repeat_freq_type'] < 1) {
        $eventdata['repeat']['event_repeat_freq_type'] = REPEAT_EVERY_DAY;
    }
    if (empty($eventdata['repeat']['event_repeat_on_num']) || $eventdata['repeat']['event_repeat_on_num'] < 1) {
        $eventdata['repeat']['event_repeat_on_num'] = REPEAT_ON_1ST;
    }
    if (empty($eventdata['repeat']['event_repeat_on_day']) || $eventdata['repeat']['event_repeat_on_day'] < 1) {
        $eventdata['repeat']['event_repeat_on_day'] = REPEAT_ON_SUN;
    }

    // endType
    $form_data['SelectedEndOn'] = ((isset($eventdata['endtype']))  && ((int) $eventdata['endtype'] == 1)) ? " checked='checked'" : '';
    $form_data['SelectedNoEnd'] = ((!isset($eventdata['endtype'])) || ((int) $eventdata['endtype'] == 0)) ? " checked='checked'" : ''; //default

    // Assign the content format (determines if scribite is in use)
    $form_data['formattedcontent'] = pnModAPIFunc('PostCalendar', 'event', 'isformatted', array(
        'func' => 'new'));

    // assign empty values to text fields that don't need changing
    $eventdata['title']     = isset($eventdata['title'])     ? $eventdata['title']     : "";
    $eventdata['hometext']  = isset($eventdata['hometext'])  ? $eventdata['hometext']  : "";
    $eventdata['contname']  = isset($eventdata['contname'])  ? $eventdata['contname']  : $eventDefaults['contname'];
    $eventdata['conttel']   = isset($eventdata['conttel'])   ? $eventdata['conttel']   : $eventDefaults['conttel'];
    $eventdata['contemail'] = isset($eventdata['contemail']) ? $eventdata['contemail'] : $eventDefaults['contemail'];
    $eventdata['website']   = isset($eventdata['website'])   ? $eventdata['website']   : $eventDefaults['website'];
    $eventdata['fee']       = isset($eventdata['fee'])       ? $eventdata['fee']       : $eventDefaults['fee'];

    $eventdata['repeat']['event_repeat_freq']    = isset($eventdata['repeat']['event_repeat_freq'])    ? $eventdata['repeat']['event_repeat_freq']    : "";
    $eventdata['repeat']['event_repeat_on_freq'] = isset($eventdata['repeat']['event_repeat_on_freq']) ? $eventdata['repeat']['event_repeat_on_freq'] : "";

    $eventdata['location_info']['event_location'] = isset($eventdata['location_info']['event_location']) ? $eventdata['location_info']['event_location'] : $eventDefaults['location']['event_location'];
    $eventdata['location_info']['event_street1']  = isset($eventdata['location_info']['event_street1'])  ? $eventdata['location_info']['event_street1']  : $eventDefaults['location']['event_street1'];
    $eventdata['location_info']['event_street2']  = isset($eventdata['location_info']['event_street2'])  ? $eventdata['location_info']['event_street2']  : $eventDefaults['location']['event_street2'];
    $eventdata['location_info']['event_city']     = isset($eventdata['location_info']['event_city'])     ? $eventdata['location_info']['event_city']     : $eventDefaults['location']['event_city'];
    $eventdata['location_info']['event_state']    = isset($eventdata['location_info']['event_state'])    ? $eventdata['location_info']['event_state']    : $eventDefaults['location']['event_state'];
    $eventdata['location_info']['event_postal']   = isset($eventdata['location_info']['event_postal'])   ? $eventdata['location_info']['event_postal']   : $eventDefaults['location']['event_postal'];

    // assign loaded data or default values
    $form_data['loaded_event'] = $eventdata;

    return $form_data;
}

/**
 * @function    postcalendar_eventapi_formateventarrayfordisplay()
 * @description This function reformats the information in an event array for proper display in detail
 * @args        $event (array) event array as pulled from the DB
 * @author      Craig Heydenburg
 *
 * @return      $event (array) modified array for display
 */
function postcalendar_eventapi_formateventarrayfordisplay($event)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if ((empty($event)) or (!is_array($event))) {
        return LogUtil::registerError(__('Required argument not present.', $dom));
    }

    //remap sharing values to global/private (this sharing map converts pre-6.0 values to 6.0+ values)
    $sharingmap = array(
        SHARING_PRIVATE  => SHARING_PRIVATE,
        SHARING_PUBLIC   => SHARING_GLOBAL,
        SHARING_BUSY     => SHARING_PRIVATE,
        SHARING_GLOBAL   => SHARING_GLOBAL,
        SHARING_HIDEDESC => SHARING_PRIVATE);
    $event['sharing'] = $sharingmap[$event['sharing']];

    $event['privateicon'] = ($event['sharing'] == SHARING_PRIVATE) ? true : false;

    // prep hometext for display
    if ($event['hometext'] == 'n/a') {
        $event['hometext'] = ':text:n/a'; // compenseate for my bad programming in previous versions CAH
    }
    $event['HTMLorTextVal'] = substr($event['hometext'], 1, 4); // HTMLorTextVal needed in edit form
    $event['hometext'] = substr($event['hometext'], 6);
    if ($event['HTMLorTextVal'] == "text") {
        $event['hometext'] = nl2br(strip_tags($event['hometext']));
    }

    // add unserialized info to event array
    $event['location_info'] = DataUtil::is_serialized($event['location'], false) ? unserialize($event['location']) : $event['location']; //on preview of formdata, location is not serialized
    $event['repeat'] = unserialize($event['recurrspec']);

    // build recurrance sentence for display
    $repeat_freq_type = explode("/", __('Day(s)/Week(s)/Month(s)/Year(s)', $dom));
    $repeat_on_num = explode("/", __('err/First/Second/Third/Fourth/Last', $dom));
    $repeat_on_day = explode(" ", __('Sun Mon Tue Wed Thu Fri Sat', $dom));
    if ($event['recurrtype'] == REPEAT) {
        $event['recurr_sentence'] = __f("Event recurs every %s", $event['repeat']['event_repeat_freq'], $dom);
        $event['recurr_sentence'] .= " " . $repeat_freq_type[$event['repeat']['event_repeat_freq_type']];
        $event['recurr_sentence'] .= " " . __("until", $dom) . " " . $event['endDate'];
    } elseif ($event['recurrtype'] == REPEAT_ON) {
        $event['recurr_sentence'] = __("Event recurs on", $dom) . " " . $repeat_on_num[$event['repeat']['event_repeat_on_num']];
        $event['recurr_sentence'] .= " " . $repeat_on_day[$event['repeat']['event_repeat_on_day']];
        $event['recurr_sentence'] .= " " . __f("of the month, every %s months", $event['repeat']['event_repeat_on_freq'], $dom);
        $event['recurr_sentence'] .= " " . __("until", $dom) . " " . $event['endDate'];
    } else {
        $event['recurr_sentence'] = __("This event does not recur.", $dom);
    }

    // build sharing sentence for display
    $event['sharing_sentence'] = ($event['sharing'] == SHARING_PRIVATE) ? __('This is a private event.', $dom) : __('This is a public event. ', $dom);

    $event['endTime'] = pnModApiFunc('PostCalendar', 'event', 'computeendtime', $event);
    // converts seconds to HH:MM for display - keep in case duration is wanted
    $event['duration'] = gmdate("G:i", $event['duration']); // stored in DB as seconds

    // prepare starttime for display HH:MM or HH:MM AP
    // for this to work, need to convert time to timestamp and then change all the templates.
    $event['sortTime']  = $event['startTime']; // save for sorting later
    list ($h, $m, $s)   = explode(':', $event['startTime']);
    $event['startTime'] = _SETTING_TIME_24HOUR ? gmdate('G:i', gmmktime($h, $m, $s, 0, 0, 0)) : gmdate('g:i a', gmmktime($h, $m, $s, 0, 0, 0));

    // format endtype for edit form
    $event['endtype'] = $event['endDate'] == '0000-00-00' ? '0' : '1';

    // compensate for changeover to new categories system
    $lang = ZLanguage::getLanguageCode();
    $event['catname']      = $event['__CATEGORIES__']['Main']['display_name'][$lang];
    $event['catcolor']     = isset($event['__CATEGORIES__']['Main']['__ATTRIBUTES__']['color'])     ? $event['__CATEGORIES__']['Main']['__ATTRIBUTES__']['color']     : '#eeeeee';
    $event['cattextcolor'] = isset($event['__CATEGORIES__']['Main']['__ATTRIBUTES__']['textcolor']) ? $event['__CATEGORIES__']['Main']['__ATTRIBUTES__']['textcolor'] : postcalendar_eventapi_color_inverse($event['catcolor']);

    // temporarily remove hometext from array
    $hometext = $event['hometext'];
    unset($event['hometext']);
    // format all the values for display
    $event = DataUtil::formatForDisplay($event);
    $event['hometext'] = DataUtil::formatForDisplayHTML($hometext); //add hometext back into array with HTML formatting

    // Hooks filtering should be after formatForDisplay to allow Hook transforms
    list ($event['hometext']) = pnModCallHooks('item', 'transform', '', array(
        $event['hometext']));

    // Check for comments
    if (pnModAvailable('EZComments') && pnModIsHooked('EZComments', 'PostCalendar')) {
        $event['commentcount'] = pnModAPIFunc('EZComments', 'user', 'countitems', array(
            'mod' => 'PostCalendar',
            'objectid' => $event['eid'],
            'status' => 0));
    } else {
        $event['commentcount'] = 0;
    }

    return $event;
}

/**
 * @function    postcalendar_eventapi_formateventarrayforDB()
 * @description This function reformats the information in an event array for insert/update in DB
 * @args        $event (array) event array as pulled from the new/edit event form
 * @author      Craig Heydenburg
 *
 * @return      $event (array) modified array for DB insert/update
 */
function postcalendar_eventapi_formateventarrayforDB($event)
{
    // convert dates to YYYY-MM-DD for DB
    $parseddatevalue    = DateUtil::parseUIDate($event['eventDate']);
    $event['eventDate'] = DateUtil::transformInternalDate($parseddatevalue);
    $parseddatevalue    = DateUtil::parseUIDate($event['endDate']);
    $event['endDate']   = DateUtil::transformInternalDate($parseddatevalue);

    if (substr($event['endDate'], 0, 4) == '0000') {
        $event['endDate'] = $event['eventDate'];
    }

    // reformat endTime to duration in seconds
    $event['duration'] = pnModApiFunc('PostCalendar', 'event', 'computeduration', $event);
    
    // reformat times from form to 'real' 24-hour format
    $startTime = $event['startTime'];
    unset($event['startTime']); // clears the whole array
    $event['startTime'] = pnModApiFunc('PostCalendar', 'event', 'convertstarttime', $startTime);

    // if event ADD perms are given to anonymous users...
    if (pnUserLoggedIn()) {
        $event['informant'] = SessionUtil::getVar('uid');
    } else {
        $event['informant'] = 1; // 'guest'
    }

    define('PC_ACCESS_ADMIN', SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_DELETE));

    // determine if the event is to be published immediately or not
    if ((bool) _SETTING_DIRECT_SUBMIT || (bool) PC_ACCESS_ADMIN || ($event['sharing'] != SHARING_GLOBAL)) {
        $event['eventstatus'] = _EVENT_APPROVED;
    } else {
        $event['eventstatus'] = _EVENT_QUEUED;
    }

    $event['endDate'] = $event['endtype'] == 1 ? $event['endDate'] : '0000-00-00';

    if (!isset($event['alldayevent'])) {
        $event['alldayevent'] = 0;
    }

    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (empty($event['hometext'])) {
        $event['hometext'] = ':text:' . __(/*!(abbr) not applicable or not available*/'n/a', $dom); // default description
    } else {
        $event['hometext'] = ':' . $event['html_or_text'] . ':' . $event['hometext']; // inserts :text:/:html: before actual content
    }

    $event['location'] = serialize($event['location']);
    if (!isset($event['recurrtype'])) {
        $event['recurrtype'] = NO_REPEAT;
    }
    $event['recurrspec'] = serialize($event['repeat']);

    $event['url'] = isset($event['url']) ? makeValidURL($event['url']) : '';

    return $event;
}

/**
 * @function    postcalendar_eventapi_validateformdata()
 * @description This function validates the data that has been submitted in the new/edit event form
 * @args        $submitted_event (array) event array as submitted
 * @author      Craig Heydenburg
 *
 * @return      $abort (bool) default=false. true if data does not validate.
 */
function postcalendar_eventapi_validateformdata($submitted_event)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    // title must be present
    if (empty($submitted_event['title'])) {
        LogUtil::registerError(__(/*!This is the field name from pntemplates/event/postcalendar_event_submit.htm:22*/"'Title' is a required field.", $dom) . '<br />');
        return true;
    }

    // check repeating frequencies
    if ($submitted_event['recurrtype'] == REPEAT) {
        if (!is_numeric($submitted_event['repeat']['event_repeat_freq'])) {
            LogUtil::registerError(__('Error! The repetition frequency must be an integer.', $dom));
            return true;
        }
        if (!isset($submitted_event['repeat']['event_repeat_freq']) || $submitted_event['repeat']['event_repeat_freq'] < 1 || empty($submitted_event['repeat']['event_repeat_freq'])) {
            LogUtil::registerError(__('Error! The repetition frequency must be at least 1.', $dom));
            return true;
        }
    } elseif ($submitted_event['recurrtype'] == REPEAT_ON) {
        if (!is_numeric($submitted_event['repeat']['event_repeat_on_freq'])) {
            LogUtil::registerError(__('Error! The repetition frequency must be an integer.', $dom));
            return true;
        }
        if (!isset($submitted_event['repeat']['event_repeat_on_freq']) || $submitted_event['repeat']['event_repeat_on_freq'] < 1 || empty($submitted_event['repeat']['event_repeat_on_freq'])) {
            LogUtil::registerError(__('Error! The repetition frequency must be at least 1.', $dom));
            return true;
        }
    }

    // check date validity
    $sdate = strtotime($submitted_event['eventDate']);
    $edate = strtotime($submitted_event['endDate']);
    $tdate = strtotime(date('Y-m-d'));

    if (($submitted_event['endtype'] == 1) && ($edate < $sdate)) {
        LogUtil::registerError(__('Error! The selected start date falls after the selected end date.', $dom));
        return true;
    }

    // check time validity
    if ($submitted_event['alldayevent'] == 0) {
        $stime = pnModApiFunc('PostCalendar', 'event', 'converttimetoseconds', $submitted_event['startTime']);
        $etime = pnModApiFunc('PostCalendar', 'event', 'converttimetoseconds', $submitted_event['endTime']);
        if ($etime <= $stime) {
            LogUtil::registerError(__('Error! The end time must be after the start time.', $dom));
            return true;
        }
    }

    return false;
}

/**
 * makeValidURL()
 * returns 'improved' url based on input string
 * checks to make sure scheme is present
 * @private
 * @returns string
 */

function makeValidURL($s)
{
    if (empty($s)) {
        return '';
    }
    if (!preg_match('|^http[s]?:\/\/|i', $s)) {
        $s = 'http://' . $s;
    }
    return $s;
}

/**
 * postcalendar_eventapi_dateIncrement()
 * returns the next valid date for an event based on the
 * current day,month,year,freq and type
 * @returns string YYYY-MM-DD
 */
function postcalendar_eventapi_dateIncrement($args)
{
    $d = $args['d']; // day
    $m = $args['m']; // month
    $y = $args['y']; // year
    $f = $args['f']; // freq
    $t = $args['t']; // type
    if ($t == REPEAT_EVERY_DAY) {
        return date('Y-m-d', mktime(0, 0, 0, $m, ($d + $f), $y));
    } elseif ($t == REPEAT_EVERY_WEEK) {
        return date('Y-m-d', mktime(0, 0, 0, $m, ($d + (7 * $f)), $y));
    } elseif ($t == REPEAT_EVERY_MONTH) {
        return date('Y-m-d', mktime(0, 0, 0, ($m + $f), $d, $y));
    } elseif ($t == REPEAT_EVERY_YEAR) {
        return date('Y-m-d', mktime(0, 0, 0, $m, $d, ($y + $f)));
    }
}

/**
 * postcalendar_eventapi_isformatted
 * This function is copied directly from the News module
 * credits to Jorn Wildt, Mark West, Philipp Niethammer or whoever wrote it
 *
 * @purpose analyze if the module has an Scribite! editor assigned
 * @param string func the function to check
 * @return bool
 * @access public
 */
function postcalendar_eventapi_isformatted($args)
{
    if (!isset($args['func'])) {
        $args['func'] = 'all';
    }

    if (pnModAvailable('scribite')) {
        $modinfo = pnModGetInfo(pnModGetIDFromName('scribite'));
        if (version_compare($modinfo['version'], '2.2', '>=')) {
            $apiargs = array(
                'modulename' => 'PostCalendar'); // parameter handling corrected in 2.2
        } else {
            $apiargs = 'PostCalendar'; // old direct parameter
        }

        $modconfig = pnModAPIFunc('scribite', 'user', 'getModuleConfig', $apiargs);
        if (in_array($args['func'], (array) $modconfig['modfuncs']) && $modconfig['modeditor'] != '-') {
            return true;
        }
    }
    return false;
}

/**
 * @description generate the inverse color
 * @author      Jonas John
 * @link        http://www.jonasjohn.de/snippets/php/color-inverse.htm
 * @license     public domain
 * @created     06/13/2006
 * @params      (string) hex color (e.g. #ffffff)
 * @return      (string) hex color (e.g. #000000)
 **/
function postcalendar_eventapi_color_inverse($color)
{
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6) {
        return '000000';
    }
    $rgb = '';
    for ($x = 0; $x < 3; $x++) {
        $c = 255 - hexdec(substr($color, (2 * $x), 2));
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0' . $c : $c;
    }
    return '#' . $rgb;
}

function postcalendar_eventapi_geteventdates($event)
{
    list ($eventstartyear, $eventstartmonth, $eventstartday) = explode('-', $event['eventDate']);
    // determine the stop date for this event
    $default_end_date = date("Y-m-d", strtotime("+2 years")); // default to only get first two years of recurrance
    $stop = ($event['endDate'] == '0000-00-00') ? $default_end_date : $event['endDate'];

    $start_date = $event['eventDate']; // maybe try today instead?

    $eventdates = array(); // placeholder array for all event dates

    $Date_Calc = new Date_Calc();

    switch ($event['recurrtype']) {
        // Events that do not repeat only have a startday (eventDate)
        case NO_REPEAT:
            return array($event['eventDate']); // there is only one date - return it
            break;
        case REPEAT:
            $rfreq = $event['repeat']['event_repeat_freq']; // could be any int
            $rtype = $event['repeat']['event_repeat_freq_type']; // REPEAT_EVERY_DAY (0), REPEAT_EVERY_WEEK (1), REPEAT_EVERY_MONTH (2), REPEAT_EVERY_YEAR (3)
            // we should bring the event up to date to make this a tad bit faster
            // any ideas on how to do that, exactly??? dateToDays probably. (RNG <5.0)
            $newyear   = $eventstartyear;
            $newmonth  = $eventstartmonth;
            $newday    = $eventstartday;
            $occurance = $Date_Calc->dateFormat($newday, $newmonth, $newyear, '%Y-%m-%d');
            while ($occurance < $start_date) {
                $occurance = postcalendar_eventapi_dateIncrement(array(
                    'd' => $newday, 
                    'm' => $newmonth, 
                    'y' => $newyear, 
                    'f' => $rfreq, 
                    't' => $rtype));
                list ($newyear, $newmonth, $newday) = explode('-', $occurance);
            }
            while ($occurance <= $stop) {
                $eventdates[] = $occurance;
                $occurance = postcalendar_eventapi_dateIncrement(array(
                    'd' => $newday, 
                    'm' => $newmonth, 
                    'y' => $newyear, 
                    'f' => $rfreq, 
                    't' => $rtype));
                list ($newyear, $newmonth, $newday) = explode('-', $occurance);
            }
            break;
        case REPEAT_ON:
            $rfreq = $event['repeat']['event_repeat_on_freq'];
            $rnum = $event['repeat']['event_repeat_on_num'];
            $rday = $event['repeat']['event_repeat_on_day'];
            $newmonth = $eventstartmonth;
            $newyear = $eventstartyear;
            $newday = $eventstartday;
            // make us current
            $currentyear = date('Y');
            while ($newyear < $currentyear) {
                $occurance = date('Y-m-d', mktime(0, 0, 0, $newmonth + $rfreq, $newday, $newyear));
                list ($newyear, $newmonth, $newday) = explode('-', $occurance);
            }
            // populate the event array
            while ($newyear <= $currentyear) {
                $dnum = $rnum; // get day event repeats on
                do {
                    $occurance = $Date_Calc->NWeekdayOfMonth($dnum--, $rday, $newmonth, $newyear, "%Y-%m-%d");
                } while ($occurance === -1);
                if ($occurance <= $stop) {
                    $eventdates[] = $occurance;
                }
                $occurance = date('Y-m-d', mktime(0, 0, 0, $newmonth + $rfreq, $newday, $newyear));
                list ($newyear, $newmonth, $newday) = explode('-', $occurance);
            }
            break;
    }
    $today = date('Y-m-d');
    foreach ($eventdates as $key => $date) {
        if ($date < $today) {
            unset ($eventdates[$key]);
        }
    }

    return $eventdates;
}
/**
 * @description take id from locations module and inserts data into correct fields in PostCalendar
 * @author      Craig Heydenburg
 * @created     06/25/2010
 * @params      (array) $event event array
 * @return      (array) $event event array (modified)
 * @note        locations ID is discarded and not available to edit later
 **/
function postcalendar_eventapi_correctlocationdata($event)
{
    if ((int) $event['location']['locations_id'] > 0) {
        $locargs = array('locationid' => $event['location']['locations_id']);
        $locObj = pnModApiFunc('Locations','user','getLocationByID',$locargs);

        $event['location']['event_location'] = $locObj['name'];
        $event['location']['event_street1']  = $locObj['street'];
        $event['location']['event_street2']  = $locObj[''];
        $event['location']['event_city']     = $locObj['city'];
        $event['location']['event_state']    = $locObj['state'];
        $event['location']['event_postal']   = $locObj['zip'];

        $event['conttel']   = isset($event['conttel'])   ? $event['conttel']   : $locObj['phone'];
        $event['contemail'] = isset($event['contemail']) ? $event['contemail'] : $locObj['email'];
        $event['website']   = isset($event['website'])   ? $event['website']   : $locObj['url'];
    }
    return $event;
}
/**
 * @description convert time like 3:00 to seconds
 * @author      Craig Heydenburg
 * @created     06/29/2010
 * @params      (array) $time array(Hour, Minute, Meridian (opt))
 * @return      (int) seconds
 **/
function postcalendar_eventapi_converttimetoseconds($time)
{
    if (isset($time['Meridian']) && !empty($time['Meridian'])) {
        if ($time['Meridian'] == "am") {
            $time['Hour'] = $time['Hour'] == 12 ? '00' : $time['Hour'];
        } else {
            $time['Hour'] = $time['Hour'] != 12 ? $time['Hour'] += 12 : $time['Hour'];
        }
    }
    return (60 * 60 * $time['Hour']) + (60 * $time['Minute']);
}
/**
 * @description convert time array to HH:MM:SS
 * @author      Craig Heydenburg
 * @created     06/29/2010
 * @params      (array) $time array(Hour, Minute, Meridian)
 * @return      (string) 'HH:MM:SS'
 **/
function postcalendar_eventapi_convertstarttime($time)
{
    if ((bool) !_SETTING_TIME_24HOUR) {
        if ($time['Meridian'] == "am") {
            $time['Hour'] = $time['Hour'] == 12 ? '00' : $time['Hour'];
        } else {
            $time['Hour'] = $time['Hour'] != 12 ? $time['Hour'] += 12 : $time['Hour'];
        }
    }
    return sprintf('%02d', $time['Hour']) . ':' . sprintf('%02d', $time['Minute']) . ':00';
}
/**
 * @description create event sharing select box
 * @author      Craig Heydenburg
 * @created     06/29/2010
 * @return      (array) key=>value pairs for selectbox
 **/
function postcalendar_eventapi_sharingselect()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    $data = array();
    if (_SETTING_ALLOW_USER_CAL) {
        $data[SHARING_PRIVATE] = __('Private', $dom);
    }
    if (SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN) || !_SETTING_ALLOW_USER_CAL) {
        $data[SHARING_GLOBAL] = __('Global', $dom);
    }
    return $data;
}
/**
 * @description determine which event type is selected and prepare html code
 * @author      Craig Heydenburg
 * @created     06/29/2010
 * @params      (bool) $alldayevent
 * @return      (array) key=>value pairs for selectbox
 **/
function postcalendar_eventapi_alldayselect($alldayevent)
{
    $eventDefaults = pnModGetVar('PostCalendar', 'pcEventDefaults');
    $selected = array();
    $selected['allday'] = (((isset($alldayevent)) && ($alldayevent == 1)) || ((!isset($alldayevent)) && ($eventDefaults['alldayevent'] == 1))) ? " checked='checked'" : '';
    $selected['timed']  = (((!isset($alldayevent)) && ($eventDefaults['alldayevent'] == 0)) || ((isset($alldayevent)) && ($alldayevent == 0))) ? " checked='checked'" : ''; //default

    return $selected;
}
/**
 * @description compute endTime from startTime and duration
 * @author      Craig Heydenburg
 * @created     06/30/2010
 * @params      (array) $event
 * @return      (string) endTime formatted (HH:MM or HH:MM AP)
 **/
function postcalendar_eventapi_computeendtime($event)
{
    list ($h, $m, $s)   = explode(':', $event['startTime']); // HH:MM:SS
    $startTime = pnModApiFunc('PostCalendar', 'event', 'converttimetoseconds', array('Hour' => $h, 'Minute' => $m));
    $endTime   = $startTime + $event['duration']; // duation already in seconds
    return _SETTING_TIME_24HOUR ? gmdate('G:i', $endTime) : gmdate('g:i a', $endTime);
}
/**
 * @description compute duration from startTime and endTime
 * @author      Craig Heydenburg
 * @created     06/30/2010
 * @params      (array) $event
 * @return      (string) duration in seconds
 **/
function postcalendar_eventapi_computeduration($event)
{
    $stime = pnModApiFunc('PostCalendar', 'event', 'converttimetoseconds', $event['startTime']);
    $etime = pnModApiFunc('PostCalendar', 'event', 'converttimetoseconds', $event['endTime']);
    return $etime - $stime;
}