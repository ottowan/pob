<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnadmin.php $
 * @version     $Id: pnadmin.php 763 2010-08-10 20:01:28Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

include_once 'modules/PostCalendar/global.php';

/**
 * the main administration function
 * This function is the default function, and is called whenever the
 * module is initiated without defining arguments.
 */
function postcalendar_admin_main()
{
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }
    return postcalendar_admin_modifyconfig();
}

/**
 * @function    postcalendar_admin_modifyconfig
 * @description present administrator options to change module configuration
 * @return      config template
 */
function postcalendar_admin_modifyconfig()
{
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Turn off template caching here
    $render = pnRender::getInstance('PostCalendar', false);

    $modinfo = pnModGetInfo(pnModGetIDFromName('PostCalendar'));
    $render->assign('postcalendarversion', $modinfo['version']);

    $render->assign('pcFilterYearStart', pnModGetVar('PostCalendar', 'pcFilterYearStart', 1));
    $render->assign('pcFilterYearEnd', pnModGetVar('PostCalendar', 'pcFilterYearEnd', 2));

    return $render->fetch('admin/postcalendar_admin_modifyconfig.htm');
}

/**
 * @function    postcalendar_admin_listapproved
 * @description list all events that have been previously approved
 * @return      list of events
 */
function postcalendar_admin_listapproved()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    $args = array();
    $args['type']     = _EVENT_APPROVED;
    $args['function'] = 'listapproved';
    $args['title']    = __('Approved events administration', $dom);
    return postcalendar_admin_showlist($args);
}

/**
 * @function    postcalendar_admin_listhidden
 * @description list all events that are currently hidden
 * @return      list of events
 */
function postcalendar_admin_listhidden()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    $args = array();
    $args['type']     = _EVENT_HIDDEN;
    $args['function'] = 'listhidden';
    $args['title']    = __('Hidden events administration', $dom);
    return postcalendar_admin_showlist($args);
}

/**
 * @function    postcalendar_admin_listqueued
 * @description list all events that are awaiting approval
 * @return      list of events
 */
function postcalendar_admin_listqueued()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    $args = array();
    $args['type']     = _EVENT_QUEUED;
    $args['function'] = 'listqueued';
    $args['title']    = __('Queued events administration', $dom);
    return postcalendar_admin_showlist($args);
}

/**
 * @function    postcalendar_admin_showlist
 * @description list events as requested/filtered
 *              send list to template
 * @return      showlist template
 */
function postcalendar_admin_showlist($args)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // $args should be array with keys 'type', 'function', 'title'
    if (!isset($args['type']) or empty($args['function'])) {
        return false; // $title not required, type can be 1, 0, -1
    }


    $offset_increment = _SETTING_HOW_MANY_EVENTS;
    if (empty($offset_increment)) {
        $offset_increment = 15;
    }

    $offset = FormUtil::getPassedValue('offset', 0);
    $sort   = FormUtil::getPassedValue('sort', 'time');
    $sdir   = FormUtil::getPassedValue('sdir', 1);
    $original_sdir = $sdir;
    $sdir = $sdir ? 0 : 1; //if true change to false, if false change to true
    if ($sdir == 0) {
        $sort .= ' DESC';
    }
    if ($sdir == 1) {
        $sort .= ' ASC';
    }

    $events = DBUtil::selectObjectArray('postcalendar_events', "WHERE pc_eventstatus=" . $args['type'], $sort, $offset, $offset_increment, false);

    // Turn off template caching here
    $render = pnRender::getInstance('PostCalendar', false);
    $render->assign('title', $args['title']);
    $render->assign('function', $args['function']);
    $render->assign('functionname', substr($args['function'], 4));
    $render->assign('events', $events);
    $render->assign('title_sort_url', pnModUrl('PostCalendar', 'admin', $args['function'], array(
        'sort' => 'title',
        'sdir' => $sdir)));
    $render->assign('time_sort_url', pnModUrl('PostCalendar', 'admin', $args['function'], array(
        'sort' => 'time',
        'sdir' => $sdir)));
    $render->assign('formactions', array(
        _ADMIN_ACTION_VIEW => __('List', $dom),
        _ADMIN_ACTION_APPROVE => __('Approve', $dom),
        _ADMIN_ACTION_HIDE => __('Hide', $dom),
        _ADMIN_ACTION_DELETE => __('Delete', $dom)));
    $render->assign('actionselected', _ADMIN_ACTION_VIEW);
    if ($offset > 1) {
        $prevlink = pnModUrl('PostCalendar', 'admin', $args['function'], array(
            'offset' => $offset - $offset_increment,
            'sort' => $sort,
            'sdir' => $original_sdir));
    } else {
        $prevlink = false;
    }
    $render->assign('prevlink', $prevlink);
    if (count($events) >= $offset_increment) {
        $nextlink = pnModUrl('PostCalendar', 'admin', $args['function'], array(
            'offset' => $offset + $offset_increment,
            'sort' => $sort,
            'sdir' => $original_sdir));
    } else {
        $nextlink = false;
    }
    $render->assign('nextlink', $nextlink);
    $render->assign('offset_increment', $offset_increment);

    return $render->fetch('admin/postcalendar_admin_showlist.htm');
}

/**
 * @function    postcalendar_admin_adminevents
 * @description allows admin to revue selected events then take action
 * @return      adminrevue template
 */
function postcalendar_admin_adminevents()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    $action  = FormUtil::getPassedValue('action');
    $events  = FormUtil::getPassedValue('events'); // could be an array or single val
    $thelist = FormUtil::getPassedValue('thelist');

    if (!isset($events)) {
        LogUtil::registerError(__('Please select an event.', $dom));

        // return to where we came from
        switch ($thelist) {
            case 'listqueued':
                return pnModFunc('PostCalendar', 'admin', 'showlist', array(
                    'type' => _EVENT_QUEUED,
                    'function' => 'showlist'));
            case 'listhidden':
                return pnModFunc('PostCalendar', 'admin', 'showlist', array(
                    'type' => _EVENT_HIDDEN,
                    'function' => 'showlist'));
            case 'listapproved':
                return pnModFunc('PostCalendar', 'admin', 'showlist', array(
                    'type' => _EVENT_APPROVED,
                    'function' => 'showlist'));
        }
    }

    if (!is_array($events)) {
        $events = array(
            $events);
    } //create array if not already

    foreach ($events as $eid) {
        // get event info
        $eventitems = DBUtil::selectObjectByID('postcalendar_events', $eid, 'eid');
        $eventitems = pnModAPIFunc('PostCalendar', 'event', 'formateventarrayfordisplay', $eventitems);
        $alleventinfo[$eid] = $eventitems;
    }

    $count = count($events);
    $function = '';
    switch ($action) {
        case _ADMIN_ACTION_APPROVE:
            $function = 'approveevents';
            $are_you_sure_text = _n('Do you really want to approve this event?', 'Do you really want to approve these events?', $count, $dom);
            break;
        case _ADMIN_ACTION_HIDE:
            $function = 'hideevents';
            $are_you_sure_text = _n('Do you really want to hide this event?', 'Do you really want to hide these events?', $count, $dom);
            break;
        case _ADMIN_ACTION_DELETE:
            $function = 'deleteevents';
            $are_you_sure_text = _n('Do you really want to delete this event?', 'Do you really want to delete these events?', $count, $dom);
            break;
    }

    // Turn off template caching here
    $render = pnRender::getInstance('PostCalendar', false);

    $render->assign('function', $function);
    $render->assign('areyousure', $are_you_sure_text);
    $render->assign('alleventinfo', $alleventinfo);

    return $render->fetch("admin/postcalendar_admin_eventrevue.htm");
}

/**
 * @function    postcalendar_admin_resetDefaults
 * @description reset all module variables to default values as defined in pninit.php
 * @return      status/error ->back to modify config page
 */
function postcalendar_admin_resetDefaults()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $defaults = pnModFunc('PostCalendar', 'init', 'getdefaults');
    if (!count($defaults)) {
        return LogUtil::registerError(__('Error! Could not load default values.', $dom));
    }

    // delete all the old vars
    pnModDelVar('PostCalendar');

    // set the new variables
    pnModSetVars('PostCalendar', $defaults);

    // clear the cache
    pnModAPIFunc('PostCalendar', 'admin', 'clearCache');

    LogUtil::registerStatus(__('Done! PostCalendar configuration reset to use default values.', $dom));
    return postcalendar_admin_modifyconfig();
}

/**
 * @function    postcalendar_admin_updateconfig
 * @description sets module variables as requested by admin
 * @return      status/error ->back to modify config page
 */
function postcalendar_admin_updateconfig()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $defaults = pnModFunc('PostCalendar', 'init', 'getdefaults');
    if (!count($defaults)) {
        return LogUtil::registerError(__('Error! Could not load default values.', $dom));
    }

    $settings = array(
        'pcTime24Hours'           => FormUtil::getPassedValue('pcTime24Hours', 0),
        'pcEventsOpenInNewWindow' => FormUtil::getPassedValue('pcEventsOpenInNewWindow', 0),
        'pcFirstDayOfWeek'        => FormUtil::getPassedValue('pcFirstDayOfWeek', $defaults['pcFirstDayOfWeek']),
        'pcUsePopups'             => FormUtil::getPassedValue('pcUsePopups', 0),
        'pcAllowDirectSubmit'     => FormUtil::getPassedValue('pcAllowDirectSubmit', 0),
        'pcListHowManyEvents'     => FormUtil::getPassedValue('pcListHowManyEvents', $defaults['pcListHowManyEvents']),
        'pcEventDateFormat'       => FormUtil::getPassedValue('pcEventDateFormat', $defaults['pcEventDateFormat']),
        'pcAllowUserCalendar'     => FormUtil::getPassedValue('pcAllowUserCalendar', 0),
        'pcTimeIncrement'         => FormUtil::getPassedValue('pcTimeIncrement', $defaults['pcTimeIncrement']),
        'pcDefaultView'           => FormUtil::getPassedValue('pcDefaultView', $defaults['pcDefaultView']),
        'pcNotifyAdmin'           => FormUtil::getPassedValue('pcNotifyAdmin', 0),
        'pcNotifyEmail'           => FormUtil::getPassedValue('pcNotifyEmail', $defaults['pcNotifyEmail']),
        'pcListMonths'            => abs((int) FormUtil::getPassedValue('pcListMonths', $defaults['pcListMonths'])),
        'pcNotifyAdmin2Admin'     => FormUtil::getPassedValue('pcNotifyAdmin2Admin', 0),
        'pcAllowCatFilter'        => FormUtil::getPassedValue('pcAllowCatFilter', 0),
        'enablecategorization'    => FormUtil::getPassedValue('enablecategorization', 0),
        'enablenavimages'         => FormUtil::getPassedValue('enablenavimages', 0),
        'enablelocations'         => FormUtil::getPassedValue('enablelocations', 0),
        'pcFilterYearStart'       => abs((int) FormUtil::getPassedValue('pcFilterYearStart', $defaults['pcFilterYearStart'])), // ensures positive value
        'pcFilterYearEnd'         => abs((int) FormUtil::getPassedValue('pcFilterYearEnd', $defaults['pcFilterYearEnd'])), // ensures positive value
        'pcNotifyPending'         => FormUtil::getPassedValue('pcNotifyPending', 0),
    );
    $settings['pcNavDateOrder'] = pnModAPIFunc('PostCalendar', 'admin', 'getdateorder', $settings['pcEventDateFormat']);
    // save out event default settings so they are not cleared
    $settings['pcEventDefaults'] = pnModGetVar('PostCalendar', 'pcEventDefaults');

    // delete all the old vars
    pnModDelVar('PostCalendar');

    // set the new variables
    pnModSetVars('PostCalendar', $settings);

    // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module', 'updateconfig', 'PostCalendar', array(
        'module' => 'PostCalendar'));

    // clear the cache
    pnModAPIFunc('PostCalendar', 'admin', 'clearCache');

    LogUtil::registerStatus(__('Done! Updated the PostCalendar configuration.', $dom));
    return postcalendar_admin_modifyconfig();
}

/*
 * postcalendar_admin_approveevents
 * update status of events so that they are viewable by users
 *
 */
function postcalendar_admin_approveevents()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    $pc_eid = FormUtil::getPassedValue('pc_eid');
    if (!is_array($pc_eid)) {
        return __("Error! An 'unidentified error' occurred.", $dom);
    }

    // structure array for DB interaction
    $eventarray = array();
    foreach ($pc_eid as $eid) {
        $eventarray[$eid] = array(
            'eid' => $eid,
            'eventstatus' => _EVENT_APPROVED);
    }
    $count = count($pc_eid);

    // update the DB
    $res = DBUtil::updateObjectArray($eventarray, 'postcalendar_events', 'eid');
    if ($res) {
        LogUtil::registerStatus(_fn('Done! %s event approved.', 'Done! %s events approved.', $count, $count, $dom));
    } else {
        LogUtil::registerError(__("Error! An 'unidentified error' occurred.", $dom));
    }

    pnModAPIFunc('PostCalendar', 'admin', 'clearCache');
    return pnModFunc('PostCalendar', 'admin', 'showlist', array(
        'type' => _EVENT_APPROVED,
        'function' => 'listapproved',
        'title' => __('Approved events administration', $dom)));
}

/*
 * postcalendar_admin_hideevents
 * update status of events so that they are hidden from view
 *
 */
function postcalendar_admin_hideevents()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    $pc_eid = FormUtil::getPassedValue('pc_eid');
    if (!is_array($pc_eid)) {
        return __("Error! An 'unidentified error' occurred.", $dom);
    }

    // structure array for DB interaction
    $eventarray = array();
    foreach ($pc_eid as $eid) {
        $eventarray[$eid] = array(
            'eid' => $eid,
            'eventstatus' => _EVENT_HIDDEN);
    }
    $count = count($pc_eid);

    // update the DB
    $res = DBUtil::updateObjectArray($eventarray, 'postcalendar_events', 'eid');
    if ($res) {
        LogUtil::registerStatus(_fn('Done! %s event was hidden.', 'Done! %s events were hidden.', $count, $count, $dom));
    } else {
        LogUtil::registerError(__("Error! An 'unidentified error' occurred.", $dom));
    }

    pnModAPIFunc('PostCalendar', 'admin', 'clearCache');
    return pnModFunc('PostCalendar', 'admin', 'showlist', array(
        'type' => _EVENT_APPROVED,
        'function' => 'listapproved',
        'title' => __('Approved events administration', $dom)));
}

/*
 * postcalendar_admin_deleteevents
 * delete array of events
 *
 */
function postcalendar_admin_deleteevents()
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    $pc_eid = FormUtil::getPassedValue('pc_eid');
    if (!is_array($pc_eid)) {
        return __("Error! An 'unidentified error' occurred.", $dom);
    }

    // structure array for DB interaction
    $eventarray = array();
    foreach ($pc_eid as $eid) {
        $eventarray[$eid] = $eid;
    }
    $count = count($pc_eid);

    // update the DB
    $res = DBUtil::deleteObjectsFromKeyArray($eventarray, 'postcalendar_events', 'eid');
    if ($res) {
        LogUtil::registerStatus(_fn('Done! %s event deleted.', 'Done! %s events deleted.', $count, $count, $dom));
    } else {
        LogUtil::registerError(__("Error! An 'unidentified error' occurred.", $dom));
    }

    pnModAPIFunc('PostCalendar', 'admin', 'clearCache');
    return pnModFunc('PostCalendar', 'admin', 'showlist', array(
        'type' => _EVENT_APPROVED,
        'function' => 'listapproved',
        'title' => __('Approved events administration', $dom)));
}

/**
 * @function    postcalendar_admin_modifyeventdefaults
 * @description present administrator options to change event default values
 * @return      template
 */
function postcalendar_admin_modifyeventdefaults()
{
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Turn off template caching here
    $render = pnRender::getInstance('PostCalendar', false);

    $eventDefaults = pnModGetVar('PostCalendar', 'pcEventDefaults');

    // load the category registry util
    if (!Loader::loadClass('CategoryRegistryUtil')) {
        pn_exit(__f('Error! Unable to load class [%s]', 'CategoryRegistryUtil'));
    }
    $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
    $render->assign('catregistry', $catregistry);

    $props = array_keys($catregistry);
    $render->assign('firstprop', $props[0]);
    $selectedDefaultCategories = $eventDefaults['categories'];
    $render->assign('selectedDefaultCategories', $selectedDefaultCategories);

    // convert duration to HH:MM
    $eventDefaults['endTime']  = pnModApiFunc('PostCalendar', 'event', 'computeendtime', $eventDefaults);

    // sharing selectbox
    $render->assign('sharingselect', pnModApiFunc('PostCalendar', 'event', 'sharingselect'));

    $render->assign('Selected',  pnModApiFunc('PostCalendar', 'event', 'alldayselect', $eventDefaults['alldayevent']));

    $render->assign('postcalendar_eventdefaults', $eventDefaults);

    return $render->fetch('admin/postcalendar_admin_eventdefaults.htm');
}

/**
 * @function    postcalendar_admin_seteventdefaults
 * @description sets module variables as requested by admin
 * @return      status/error ->back to event defaults config page
 */
function postcalendar_admin_seteventdefaults()
{
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $eventDefaults = FormUtil::getPassedValue('postcalendar_eventdefaults'); //array

    // filter through locations translator
    $eventDefaults = pnModApiFunc('postcalendar', 'event', 'correctlocationdata', $eventDefaults);

    //convert times to storable values
    $eventDefaults['duration'] = pnModApiFunc('PostCalendar', 'event', 'computeduration', $eventDefaults);
    $eventDefaults['duration'] = ($eventDefaults['duration'] > 0) ? $eventDefaults['duration'] : 3600; //disallow duration < 0

    $startTime = $eventDefaults['startTime'];
    unset($eventDefaults['startTime']); // clears the whole array
    $eventDefaults['startTime'] = pnModApiFunc('PostCalendar', 'event', 'convertstarttime', $startTime);

    // save the new values
    pnModSetVar('PostCalendar', 'pcEventDefaults', $eventDefaults);

    LogUtil::registerStatus(__('Done! Updated the PostCalendar event default values.', $dom));
    return postcalendar_admin_modifyeventdefaults();
}