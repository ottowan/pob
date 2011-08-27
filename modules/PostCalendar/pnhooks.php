<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnhooks.php $
 * @version     $Id: pnhooks.php 583 2010-03-03 00:02:15Z craigh $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * postcalendar_hooks_new
 *
 * display PostCalendar related information on hooked new item
 * @param array $args
 * @return string generated html output
 * @access public
 */
function postcalendar_hooks_new($args)
{
    $thismodule = isset($args['extrainfo']['module']) ? strtolower($args['extrainfo']['module']) : strtolower(pnModGetName()); // default to active module

    $render = pnRender::getInstance('PostCalendar');
    $postcalendar_admincatselected = pnModGetVar($thismodule, 'postcalendar_admincatselected');
    $postcalendar_optoverride = pnModGetVar($thismodule, 'postcalendar_optoverride', false);

    if (($postcalendar_admincatselected['Main'] > 0) && (!$postcalendar_optoverride)) {
        $postcalendar_hide = true;
    } else {
        $postcalendar_hide = false;
    }
    $render->assign('postcalendar_hide', $postcalendar_hide);

    if ($postcalendar_admincatselected['Main'] == 0) {
        // load the category registry util
        if (Loader::loadClass('CategoryRegistryUtil')) {
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
            $render->assign('postcalendar_catregistry', $catregistry);
        }
    } else {
        $render->assign('postcalendar_admincatselected', serialize($postcalendar_admincatselected)); // value assigned by admin
    }
    $render->assign('postcalendar_optoverride', $postcalendar_optoverride);

    return $render->fetch('hooks/postcalendar_hooks_new.htm');
}

/**
 * postcalendar_hooks_modify
 *
 * display PostCalendar related information on hooked modify item
 * @param array $args
 * @return string generated html output
 * @access public
 */
function postcalendar_hooks_modify($args)
{
    if ((!isset($args['objectid'])) || ((int) $args['objectid'] <= 0)) {
        return false;
    }
    $module = isset($args['extrainfo']['module']) ? strtolower($args['extrainfo']['module']) : strtolower(pnModGetName()); // default to active module

    // get the event
    // Get table info
    $pntable = pnDBGetTables();
    $cols = $pntable['postcalendar_events_column'];
    // build where statement
    $where = "WHERE " . $cols['hooked_modulename'] . " = '" . DataUtil::formatForStore($module) . "'
              AND "   . $cols['hooked_objectid']   . " = '" . DataUtil::formatForStore($args['objectid']) . "'";
    $event = DBUtil::selectObject('postcalendar_events', $where);

    if ($event) {
        $selectedcategories = array();
        foreach ($event['__CATEGORIES__'] as $prop => $cats) {
            $selectedcategories[$prop] = $cats['id'];
        }
        $eventid = $event['eid'];
    }

    $render = pnRender::getInstance('PostCalendar');
    $postcalendar_admincatselected = pnModGetVar($module, 'postcalendar_admincatselected');
    $postcalendar_optoverride = pnModGetVar($module, 'postcalendar_optoverride', false);

    if (($postcalendar_admincatselected['Main'] > 0) && (!$postcalendar_optoverride)) {
        $postcalendar_hide = true;
    } else {
        $postcalendar_hide = false;
    }
    $render->assign('postcalendar_hide', $postcalendar_hide);

    if ($postcalendar_admincatselected['Main'] == 0) {
        // load the category registry util
        if (Loader::loadClass('CategoryRegistryUtil')) {
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
            $render->assign('postcalendar_catregistry', $catregistry);
            $render->assign('postcalendar_selectedcategories', $selectedcategories);
        }
    } else {
        $render->assign('postcalendar_admincatselected', serialize($postcalendar_admincatselected)); // value assigned by admin
    }
    $render->assign('postcalendar_optoverride', $postcalendar_optoverride);

    $render->assign('postcalendar_eid', $eventid);

    return $render->fetch('hooks/postcalendar_hooks_modify.htm');
}
/**
 * postcalendar_hooks_modifyconfig
 *
 * display PostCalendar related information on hooked module admin modify
 * @param array $args
 * @return string generated html output
 * @access public
 */
function postcalendar_hooks_modifyconfig($args)
{
    $thismodule = isset($args['extrainfo']['module']) ? strtolower($args['extrainfo']['module']) : strtolower(pnModGetName()); // default to active module
    $render = pnRender::getInstance('PostCalendar');

    // load the category registry util
    if (Loader::loadClass('CategoryRegistryUtil')) {
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
        $render->assign('postcalendar_catregistry', $catregistry);
    }

    $render->assign('postcalendar_optoverride', pnModGetVar($thismodule, 'postcalendar_optoverride', false));
    $render->assign('postcalendar_admincatselected', pnModGetVar($thismodule, 'postcalendar_admincatselected'));
    return $render->fetch('hooks/postcalendar_hooks_modifyconfig.htm');
}
