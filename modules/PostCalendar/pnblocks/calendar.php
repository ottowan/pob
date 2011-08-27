<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnblocks/calendar.php $
 * @version     $Id: calendar.php 753 2010-08-08 23:43:08Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

include_once 'modules/PostCalendar/pnincludes/DateCalc.class.php';

/**
 * initialise block
 */
function postcalendar_calendarblock_init()
{
    SecurityUtil::registerPermissionSchema('PostCalendar:calendarblock:', 'Block title::');
}

/**
 * get information on block
 */
function postcalendar_calendarblock_info()
{
    return array(
        'text_type'      => 'PostCalendar',
        'module'         => 'PostCalendar',
        'text_type_long' => 'Calendar Block',
        'allow_multiple' => true,
        'form_content'   => false,
        'form_refresh'   => false,
        'show_preview'   => true);
}

/**
 * display block
 */
function postcalendar_calendarblock_display($blockinfo)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');
    if (!SecurityUtil::checkPermission('PostCalendar:calendarblock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
        return;
    }
    if (!pnModAvailable('PostCalendar')) {
        return;
    }

    // today's date
    $Date = DateUtil::getDatetime('', '%Y%m%d%H%M%S');

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $showcalendar   = $vars['pcbshowcalendar'];
    $showevents     = $vars['pcbeventoverview'];
    $hideevents     = $vars['pcbhideeventoverview'];
    $eventslimit    = $vars['pcbeventslimit'];
    $nextevents     = $vars['pcbnextevents'];
    $pcbshowsslinks = $vars['pcbshowsslinks'];
    $pcbeventsrange = $vars['pcbeventsrange'];
    $pcbfiltercats  = $vars['pcbfiltercats'];

    // setup the info to build this
    $the_year  = substr($Date, 0, 4);
    $the_month = substr($Date, 4, 2);
    $the_day   = substr($Date, 6, 2);

    $render = pnRender::getInstance('PostCalendar');
    $output = '';

    // If block is cached, return cached version
    $render->cache_id = $blockinfo['bid'] . ':' . pnUserGetVar('uid');
    $templates_cached = true;
    if ($showcalendar) {
        if (!$render->is_cached('blocks/postcalendar_block_view_month.htm')) {
            $templates_cached = false;
        }
    }
    if ($showevents) {
        if (!$render->is_cached('blocks/postcalendar_block_view_day.htm')) {
            $templates_cached = false;
        }
    }
    if ($nextevents) {
        if (!$render->is_cached('blocks/postcalendar_block_view_upcoming.htm')) {
            $templates_cached = false;
        }
    }
    if ($pcbshowsslinks) {
        if (!$render->is_cached('blocks/postcalendar_block_calendarlinks.htm')) {
            $templates_cached = false;
        }
    }

    if ($templates_cached) {
        $blockinfo['content'] = $render->fetch('blocks/postcalendar_block_view_month.htm');
        $blockinfo['content'] .= $render->fetch('blocks/postcalendar_block_view_day.htm');
        $blockinfo['content'] .= $render->fetch('blocks/postcalendar_block_view_upcoming.htm');
        $blockinfo['content'] .= $render->fetch('blocks/postcalendar_block_calendarlinks.htm');

        return pnBlockThemeBlock($blockinfo);
    }
    // end cache return

    // set up the next and previous months to move to
    $prev_month = DateUtil::getDatetime_NextMonth(-1, '%Y%m%d', $the_year, $the_month, 1);
    $next_month = DateUtil::getDatetime_NextMonth(1, '%Y%m%d', $the_year, $the_month, 1);
    $pc_prev = pnModURL('PostCalendar', 'user', 'view', array(
        'viewtype' => 'month',
        'Date'     => $prev_month));
    $pc_next = pnModURL('PostCalendar', 'user', 'view', array(
        'viewtype' => 'month',
        'Date'     => $next_month));
    $pc_month_name = DateUtil::strftime("%B", strtotime($Date));
    $month_link_url = pnModURL('PostCalendar', 'user', 'view', array(
        'viewtype' => 'month',
        'Date'     => date('Ymd', mktime(0, 0, 0, $the_month, 1, $the_year))));
    $month_link_text = $pc_month_name . ' ' . $the_year;

    $pc_colclasses      = array(
        0 => "pcWeekday", 
        1 => "pcWeekday", 
        2 => "pcWeekday", 
        3 => "pcWeekday", 
        4 => "pcWeekday", 
        5 => "pcWeekday", 
        6 => "pcWeekday");
    switch (_SETTING_FIRST_DAY_WEEK) {
        case _IS_MONDAY:
            $pc_array_pos = 1;
            $first_day = date('w', mktime(0, 0, 0, $the_month, 0, $the_year));
            $pc_colclasses[5] = "pcWeekend";
            $pc_colclasses[6] = "pcWeekend";
            break;
        case _IS_SATURDAY:
            $pc_array_pos = 6;
            $first_day = date('w', mktime(0, 0, 0, $the_month, 2, $the_year));
            $pc_colclasses[0] = "pcWeekend";
            $pc_colclasses[1] = "pcWeekend";
            break;
        case _IS_SUNDAY:
        default:
            $pc_array_pos = 0;
            $first_day = date('w', mktime(0, 0, 0, $the_month, 1, $the_year));
            $pc_colclasses[0] = "pcWeekend";
            $pc_colclasses[6] = "pcWeekend";
            break;
    }

    $month_view_start = date('Y-m-d', mktime(0, 0, 0, $the_month, 1, $the_year));
    $month_view_end   = date('Y-m-t', mktime(0, 0, 0, $the_month, 1, $the_year));
    $today_date       = DateUtil::getDatetime('', '%Y-%m-%d');
    $starting_date    = date('m/d/Y', mktime(0, 0, 0, $the_month, 1 - $first_day, $the_year));
    $ending_date      = date('m/t/Y', mktime(0, 0, 0, $the_month + $pcbeventsrange, 1, $the_year));

    // this grabs more events that required and could slow down the process. RNG
    // suggest addming $limit paramter to getEvents() to reduce load CAH Sept 29, 2009
    $filtercats['__CATEGORIES__'] = $pcbfiltercats; //reformat array
    $eventsByDate = pnModAPIFunc('PostCalendar', 'event', 'getEvents', array(
        'start'      => $starting_date,
        'end'        => $ending_date,
        'filtercats' => $filtercats));
    $Date_Calc = new Date_Calc();
    $calendarView = $Date_Calc->getCalendarMonth($the_month, $the_year, '%Y-%m-%d');

    $pc_short_day_names = explode (" ", __(/*!First Letter of each Day of week*/'S M T W T F S', $dom));
    $sdaynames = array();
    for ($i = 0; $i < 7; $i++) {
        if ($pc_array_pos >= 7) {
            $pc_array_pos = 0;
        }
        $sdaynames[] = $pc_short_day_names[$pc_array_pos];
        $pc_array_pos++;
    }

    if (isset($calendarView)) {
        $render->assign('CAL_FORMAT', $calendarView);
    }

    $countTodaysEvents = count($eventsByDate[$today_date]);
    $hideTodaysEvents  = ($hideevents && ($countTodaysEvents == 0)) ? true : false;

    $render->assign('S_SHORT_DAY_NAMES', $sdaynames);
    $render->assign('A_EVENTS',          $eventsByDate);
    $render->assign('todaysEvents',      $eventsByDate[$today_date]);
    $render->assign('hideTodaysEvents',  $hideTodaysEvents);
    $render->assign('PREV_MONTH_URL',    $pc_prev);
    $render->assign('NEXT_MONTH_URL',    $pc_next);
    $render->assign('MONTH_START_DATE',  $month_view_start);
    $render->assign('MONTH_END_DATE',    $month_view_end);
    $render->assign('TODAY_DATE',        $today_date);
    $render->assign('DATE',              $Date);
    $render->assign('DISPLAY_LIMIT',     $eventslimit);
    $render->assign('pc_colclasses',     $pc_colclasses);

    if ($showcalendar) {
        $output .= $render->fetch('blocks/postcalendar_block_view_month.htm');
    }

    if ($showevents) {
        if ($showcalendar) {
            $render->assign('SHOW_TITLE', 1);
        } else {
            $render->assign('SHOW_TITLE', 0);
        }
        $output .= $render->fetch('blocks/postcalendar_block_view_day.htm');
    }

    if ($nextevents) {
        if ($showcalendar || $showevents) {
            $render->assign('SHOW_TITLE', 1);
        } else {
            $render->assign('SHOW_TITLE', 0);
        }
        $output .= $render->fetch('blocks/postcalendar_block_view_upcoming.htm');
    }

    if ($pcbshowsslinks) {
        $output .= $render->fetch('blocks/postcalendar_block_calendarlinks.htm');
    }

    $blockinfo['content'] = $output;
    return pnBlockThemeBlock($blockinfo);
}

/**
 * modify block settings ..
 */
function postcalendar_calendarblock_modify($blockinfo)
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    // Defaults
    if (empty($vars['pcbshowcalendar']))      $vars['pcbshowcalendar']      = 0;
    if (empty($vars['pcbeventslimit']))       $vars['pcbeventslimit']       = 5;
    if (empty($vars['pcbeventoverview']))     $vars['pcbeventoverview']     = 0;
    if (empty($vars['pcbhideeventoverview'])) $vars['pcbhideeventoverview'] = 0;
    if (empty($vars['pcbnextevents']))        $vars['pcbnextevents']        = 0;
    if (empty($vars['pcbeventsrange']))       $vars['pcbeventsrange']       = 6;
    if (empty($vars['pcbshowsslinks']))       $vars['pcbshowsslinks']       = 0;
    if (empty($vars['pcbfiltercats']))        $vars['pcbfiltercats']        = array();

    $render = pnRender::getInstance('PostCalendar', false); // no caching

    // load the category registry util
    if (Loader::loadClass('CategoryRegistryUtil')) {
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('PostCalendar', 'postcalendar_events');
        $render->assign('catregistry', $catregistry);
    }
    $props = array_keys($catregistry);
    $render->assign('firstprop', $props[0]);

    $render->assign('vars', $vars);

    return $render->fetch('blocks/postcalendar_block_calendar_modify.htm');
}

/**
 * update block settings
 */
function postcalendar_calendarblock_update($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // overwrite with new values
    $vars['pcbshowcalendar']      = FormUtil::getPassedValue('pcbshowcalendar',      0);
    $vars['pcbeventslimit']       = FormUtil::getPassedValue('pcbeventslimit',       5);
    $vars['pcbeventoverview']     = FormUtil::getPassedValue('pcbeventoverview',     0);
    $vars['pcbhideeventoverview'] = FormUtil::getPassedValue('pcbhideeventoverview', 0);
    $vars['pcbnextevents']        = FormUtil::getPassedValue('pcbnextevents',        0);
    $vars['pcbeventsrange']       = FormUtil::getPassedValue('pcbeventsrange',       6);
    $vars['pcbshowsslinks']       = FormUtil::getPassedValue('pcbshowsslinks',       0);
    $vars['pcbfiltercats']        = FormUtil::getPassedValue('pcbfiltercats'); //array

    $render = pnRender::getInstance('PostCalendar');
    $render->clear_cache('blocks/postcalendar_block_view_day.htm');
    $render->clear_cache('blocks/postcalendar_block_view_month.htm');
    $render->clear_cache('blocks/postcalendar_block_view_upcoming.htm');
    $render->clear_cache('blocks/postcalendar_block_calendarlinks.htm');
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    return $blockinfo;
}
