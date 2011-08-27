<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnblocks/pastevents.php $
 * @version     $Id: pastevents.php 612 2010-06-22 15:15:51Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

include_once 'modules/PostCalendar/pnincludes/DateCalc.class.php';

/**
 * initialise block
 */
function postcalendar_pasteventsblock_init()
{
    SecurityUtil::registerPermissionSchema('PostCalendar:pasteventsblock:', 'Block title::');
}

/**
 * get information on block
 */
function postcalendar_pasteventsblock_info()
{
    return array(
        'text_type'      => 'PostCalendar',
        'module'         => 'PostCalendar',
        'text_type_long' => 'Past Events Block',
        'allow_multiple' => true,
        'form_content'   => false,
        'form_refresh'   => false,
        'show_preview'   => true);
}

/**
 * display block
 */
function postcalendar_pasteventsblock_display($blockinfo)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar:pasteventsblock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
        return;
    }
    if (!pnModAvailable('PostCalendar')) {
        return;
    }

    // today's date
    $Date = DateUtil::getDatetime('', '%Y%m%d%H%M%S');

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $pcbeventsrange = (int) $vars['pcbeventsrange'];
    $pcbfiltercats  = $vars['pcbfiltercats'];

    // setup the info to build this
    $the_year  = (int) substr($Date, 0, 4);
    $the_month = (int) substr($Date, 4, 2);
    $the_day   = (int) substr($Date, 6, 2);

    $render = pnRender::getInstance('PostCalendar');

    // If block is cached, return cached version
    $render->cache_id = $blockinfo['bid'] . ':' . pnUserGetVar('uid');
    if ($render->is_cached('blocks/postcalendar_block_pastevents.htm')) {
        $blockinfo['content'] = $render->fetch('blocks/postcalendar_block_pastevents.htm');
        return pnBlockThemeBlock($blockinfo);
    }

    if ($pcbeventsrange == 0) {
        $starting_date = '1/1/1970';
    } else {
        $starting_date = date('m/d/Y', mktime(0, 0, 0, $the_month - $pcbeventsrange, $the_day, $the_year));
    }
    $ending_date   = date('m/d/Y', mktime(0, 0, 0, $the_month, $the_day - 1, $the_year)); // yesterday

    $filtercats['__CATEGORIES__'] = $pcbfiltercats; //reformat array
    $eventsByDate = pnModAPIFunc('PostCalendar', 'event', 'getEvents', array(
        'start'      => $starting_date,
        'end'        => $ending_date,
        'filtercats' => $filtercats,
        'sort'       => 'DESC'));

    $render->assign('A_EVENTS',   $eventsByDate);
    $render->assign('DATE',       $Date);

    $blockinfo['content'] = $render->fetch('blocks/postcalendar_block_pastevents.htm');

    return pnBlockThemeBlock($blockinfo);
}

/**
 * modify block settings ..
 */
function postcalendar_pasteventsblock_modify($blockinfo)
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    // Defaults
    if (empty($vars['pcbeventsrange'])) $vars['pcbeventsrange'] = 6;
    if (empty($vars['pcbfiltercats']))  $vars['pcbfiltercats']  = array();

    $render = pnRender::getInstance('PostCalendar', false); // no caching

    // load the category registry util
    if (Loader::loadClass('CategoryRegistryUtil')) {
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
        $render->assign('catregistry', $catregistry);
    }
    $props = array_keys($catregistry);
    $render->assign('firstprop', $props[0]);

    $render->assign('vars', $vars);

    return $render->fetch('blocks/postcalendar_block_pastevents_modify.htm');
}

/**
 * update block settings
 */
function postcalendar_pasteventsblock_update($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // overwrite with new values
    $vars['pcbeventsrange'] = FormUtil::getPassedValue('pcbeventsrange', 6);
    $vars['pcbfiltercats']  = FormUtil::getPassedValue('pcbfiltercats'); //array

    $render = pnRender::getInstance('PostCalendar');
    $render->clear_cache('blocks/postcalendar_block_pastevents.htm');
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    return $blockinfo;
}
