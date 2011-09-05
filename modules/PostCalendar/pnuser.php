<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnuser.php $
 * @version     $Id: pnuser.php 612 2010-06-22 15:15:51Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
include_once 'modules/PostCalendar/global.php';

/**
 * postcalendar_user_main
 *
 * main view function for end user
 * @access public
 */
function postcalendar_user_main()
{
    return postcalendar_user_view();
}

/**
 * view items
 * This is a standard function to provide an overview of all of the items
 * available from the module.
 */
function postcalendar_user_view()
{
    if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_OVERVIEW)) {
        return LogUtil::registerPermissionError();
    }

    // get the vars that were passed in
    $popup       = FormUtil::getPassedValue('popup');
    $pc_username = FormUtil::getPassedValue('pc_username');
    $eid         = FormUtil::getPassedValue('eid');
    $viewtype    = FormUtil::getPassedValue('viewtype', _SETTING_DEFAULT_VIEW);
    $jumpday     = FormUtil::getPassedValue('jumpDay');
    $jumpmonth   = FormUtil::getPassedValue('jumpMonth');
    $jumpyear    = FormUtil::getPassedValue('jumpYear');
    $jumpargs    = array(
        'jumpday' => $jumpday,
        'jumpmonth' => $jumpmonth,
        'jumpyear' => $jumpyear);
    $Date        = FormUtil::getPassedValue('Date', pnModAPIFunc('PostCalendar', 'user', 'getDate', $jumpargs));
    $filtercats  = FormUtil::getPassedValue('postcalendar_events');
    $func        = FormUtil::getPassedValue('func');
    $prop        = isset($args['prop']) ? $args['prop'] : (string)FormUtil::getPassedValue('prop', null, 'GET');
    $cat         = isset($args['cat']) ? $args['cat'] : (string)FormUtil::getPassedValue('cat', null, 'GET');
    
    if (empty($filtercats) && !empty($prop) && !empty($cat)) {
        $filtercats[__CATEGORIES__][$prop] = $cat;
    }

    return postcalendar_user_display(array(
        'viewtype' => $viewtype,
        'Date' => $Date,
        'filtercats' => $filtercats,
        'pc_username' => $pc_username,
        'popup' => $popup,
        'eid' => $eid,
        'func' => $func));
}

/**
 * display item available from the module.
 */
function postcalendar_user_display($args)
{
    $viewtype    = $args['viewtype'];
    $Date        = $args['Date'];
    $filtercats  = $args['filtercats'];
    $pc_username = $args['pc_username'];
    $popup       = $args['popup'];
    $eid         = $args['eid'];
    $func        = $args['func'];

    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (empty($Date) && empty($viewtype)) {
        return LogUtil::registerError(__('Error! Required arguments not present.', $dom));
    }

    $render = pnRender::getInstance('PostCalendar');
    $modinfo = pnModGetInfo(pnModGetIDFromName('PostCalendar'));
    $render->assign('postcalendarversion', $modinfo['version']);

    $render->cache_id = $Date . '|' . $viewtype . '|' . $eid . '|' . pnUserGetVar('uid');

    switch ($viewtype) {
        case 'details':
            if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_READ)) {
                return LogUtil::registerPermissionError();
            }

            // build template and fetch:
            if ($render->is_cached('user/postcalendar_user_view_event_details.htm')) {
                // use cached version
                return $render->fetch('user/postcalendar_user_view_event_details.htm');
            } else {
                // get the event from the DB
                $event = DBUtil::selectObjectByID('postcalendar_events', $args['eid'], 'eid');
                $event = pnModAPIFunc('PostCalendar', 'event', 'formateventarrayfordisplay', $event);

                // is event allowed for this user?
                if ($event['sharing'] == SHARING_PRIVATE && $event['aid'] != pnUserGetVar('uid') && !SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
                    // if event is PRIVATE and user is not assigned event ID (aid) and user is not Admin event should not be seen
                    return LogUtil::registerError(__('You do not have permission to view this event.', $dom));
                }

                // since recurrevents are dynamically calculcated, we need to change the date
                // to ensure that the correct/current date is being displayed (rather than the
                // date on which the recurring booking was executed).
                if ($event['recurrtype']) {
                    $y = substr($args['Date'], 0, 4);
                    $m = substr($args['Date'], 4, 2);
                    $d = substr($args['Date'], 6, 2);
                    $event['eventDate'] = "$y-$m-$d";
                }
                $render->assign('loaded_event', $event);

                if ($popup == true) {
                    $render->display('user/postcalendar_user_view_popup.htm');
                    return true; // displays template without theme wrap
                } else {
                    if ((SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADD) && (pnUserGetVar('uid') == $event['aid'])) || SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
                        $render->assign('EVENT_CAN_EDIT', true);
                    } else {
                        $render->assign('EVENT_CAN_EDIT', false);
                    }
                    $render->assign('TODAY_DATE', DateUtil::getDatetime('', '%Y-%m-%d'));
                    $render->assign('DATE', $Date);
                    return $render->fetch('user/postcalendar_user_view_event_details.htm');
                }
            }
            break;

        default:
            if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_OVERVIEW)) {
                return LogUtil::registerPermissionError();
            }
            $out = pnModAPIFunc('PostCalendar', 'user', 'buildView', array(
                'Date' => $Date,
                'viewtype' => $viewtype,
                'pc_username' => $pc_username,
                'filtercats' => $filtercats,
                'func' => $func));
            // build template and fetch:
            if ($render->is_cached('user/postcalendar_user_view_' . $viewtype . '.htm')) {
                // use cached version
                return $render->fetch('user/postcalendar_user_view_' . $viewtype . '.htm');
            } else {
                foreach ($out as $var => $val) {
                    $render->assign($var, $val);
                }

                return $render->fetch('user/postcalendar_user_view_' . $viewtype . '.htm');
            } // end if/else
            break;
    } // end switch
}


function postcalendar_user_jsonInsertBooking(){
  header('Content-Type: text/html; charset=utf-8'); 
  $args = file_get_contents("php://input");
  
  //print_r($args);
  pnModAPIFunc('PostCalendar', 'user', 'insertBooking', $args);
  //echo "aaa";
  pnShutDown();

}



function postcalendar_user_checkout(){

	if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}

  
  $args = array(
				  "" => ""
		);
  pnModAPIFunc('POBHotel', 'user', 'checkout', $args);

}

