<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnhooksapi/delete.php $
 * @version     $Id: delete.php 748 2010-07-27 11:49:09Z craigh $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * delete action on hook
 *
 * @author  Craig Heydenburg
 * @return  boolean    true/false
 * @access  public
 */
function postcalendar_hooksapi_delete($args)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    if ((!isset($args['objectid'])) || ((int) $args['objectid'] <= 0)) {
        return LogUtil::registerError(__f("PostCalendar: %s not provided in delete hook", 'objectid', $dom));
    }
    $module = isset($args['extrainfo']['module']) ? strtolower($args['extrainfo']['module']) : strtolower(pnModGetName()); // default to active module

    //if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADD)) {
    //    return LogUtil::registerPermissionError();
    //}

    // Get table info
    $pntable = pnDBGetTables();
    $cols = $pntable['postcalendar_events_column'];
    // build where statement
    $where = "WHERE " . $cols['hooked_modulename'] . " = '" . DataUtil::formatForStore($module) . "'
              AND "   . $cols['hooked_objectid']   . " = '" . DataUtil::formatForStore($args['objectid']) . "'";

    //return (bool)DBUtil::deleteWhere('postcalendar_events', $where);
    if (!DBUtil::deleteObject(array(), 'postcalendar_events', $where, 'eid')) {
        return LogUtil::registerError(__('Error! Could not delete associated PostCalendar event.', $dom));
    }

    LogUtil::registerStatus(__('Associated PostCalendar event also deleted.', $dom));
    return true;
}