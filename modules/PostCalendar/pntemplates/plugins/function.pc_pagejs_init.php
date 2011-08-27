<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pntemplates/plugins/function.pc_pagejs_init.php $
 * @version     $Id: function.pc_pagejs_init.php 472 2010-01-06 01:34:38Z craigh $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
/**
 * pc_pagejs_init: include the required javascript in header if needed
 *
 * @author Craig Heydenburg
 * @param  none
 */
function smarty_function_pc_pagejs_init($params, &$smarty)
{
    unset($params);
    if (_SETTING_USE_POPUPS) {
        PageUtil::addVar("javascript", "modules/PostCalendar/pnjavascript/postcalendar_overlibconfig.js");
    }
    if (_SETTING_OPEN_NEW_WINDOW && !_SETTING_USE_POPUPS) {
        PageUtil::addVar("javascript", "modules/PostCalendar/pnjavascript/postcalendar_jspopup.js");
    }
    return;
}
