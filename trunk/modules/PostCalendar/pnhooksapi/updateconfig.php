<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnhooksapi/updateconfig.php $
 * @version     $Id: updateconfig.php 539 2010-02-04 02:03:40Z craigh $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * updateconfig action on hook
 *
 * @author  Craig Heydenburg
 * @return  boolean    true/false
 * @access  public
 */
function postcalendar_hooksapi_updateconfig($args)
{
	$hookinfo = FormUtil::getPassedValue('postcalendar', array(), 'POST'); // array of data from 'modifyconfig' hook
    if ((!isset($hookinfo['postcalendar_optoverride'])) || (empty($hookinfo['postcalendar_optoverride']))) {
        $hookinfo['postcalendar_optoverride'] = 0;
    }
    $thismodule = isset($args['extrainfo']['module']) ? strtolower($args['extrainfo']['module']) : strtolower(pnModGetName()); // default to active module
    pnModSetVars($thismodule, $hookinfo);
    // ModVars: postcalendar_admincatselected, postcalendar_optoverride

    $dom = ZLanguage::getModuleDomain('PostCalendar');
    LogUtil::registerStatus(__("PostCalendar: module config updated.", $dom));

    return;
}