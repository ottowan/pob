<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pntemplates/plugins/function.pc_url.php $
 * @version     $Id: function.pc_url.php 753 2010-08-08 23:43:08Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
function smarty_function_pc_url($args, &$smarty)
{
    $action     = array_key_exists('action',     $args) && isset($args['action'])      ? $args['action']     : _SETTING_DEFAULT_VIEW;
    $print      = array_key_exists('print',      $args) && !empty($args['print'])      ? true                : false;
    $date       = array_key_exists('date',       $args) && !empty($args['date'])       ? $args['date']       : null;
    $full       = array_key_exists('full',       $args) && !empty($args['full'])       ? true                : false;
    $class      = array_key_exists('class',      $args) && !empty($args['class'])      ? $args['class']      : null;
    $display    = array_key_exists('display',    $args) && !empty($args['display'])    ? $args['display']    : null;
    $eid        = array_key_exists('eid',        $args) && !empty($args['eid'])        ? $args['eid']        : null;
    $javascript = array_key_exists('javascript', $args) && !empty($args['javascript']) ? $args['javascript'] : null;
    $assign     = array_key_exists('assign',     $args) && !empty($args['assign'])     ? $args['assign']     : null;
    $navlink    = array_key_exists('navlink',    $args) && !empty($args['navlink'])    ? true                : false;
    $func       = array_key_exists('func',       $args) && !empty($args['func'])       ? $args['func']       : 'new';
    unset($args['action']);
    unset($args['print']);
    unset($args['date']);
    unset($args['full']);
    unset($args['class']);
    unset($args['display']);
    unset($args['eid']);
    unset($args['javascript']);
    unset($args['assign']);
    unset($args['navlink']);
    unset($args['func']);

    $dom = ZLanguage::getModuleDomain('PostCalendar');

    $viewtype = strtolower(FormUtil::getPassedValue('viewtype', _SETTING_DEFAULT_VIEW));
    if (FormUtil::getPassedValue('func') == 'new') {
        $viewtype = 'new';
    }
    $pc_username = FormUtil::getPassedValue('pc_username');

    if (is_null($date)) {
        //not sure these three lines are needed with call to getDate here
        $jumpday   = FormUtil::getPassedValue('jumpDay');
        $jumpmonth = FormUtil::getPassedValue('jumpMonth');
        $jumpyear  = FormUtil::getPassedValue('jumpYear');
        $Date      = FormUtil::getPassedValue('Date');
        $jumpargs  = array(
            'Date'      => $Date,
            'jumpday'   => $jumpday,
            'jumpmonth' => $jumpmonth,
            'jumpyear'  => $jumpyear);
        $date      = pnModAPIFunc('PostCalendar', 'user', 'getDate', $jumpargs);
    }
    // some extra cleanup if necessary
    $date = str_replace('-', '', $date);

    switch ($action) {
        case 'add':
        case 'submit':
        case 'submit-admin':
            $link = pnModURL('PostCalendar', 'event', $func, array(
                'Date' => $date));
            break;
        case 'today':
            $link = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype'    => $viewtype,
                'Date'        => DateUtil::getDatetime('', '%Y%m%d000000'),
                'pc_username' => $pc_username));
            break;
        case 'day':
        case 'week':
        case 'month':
        case 'year':
        case 'list':
            $link = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype'    => $action,
                'Date'        => $date,
                'pc_username' => $pc_username));
            break;
        case 'search':
            $link = pnModURL('Search');
            break;
        case 'print':
            $link = pnGetCurrentURL() . "&theme=Printer";
            break;
        case 'rss':
            $link = pnModURL('PostCalendar', 'user', 'view', array(
                'viewtype' => 'xml',
                'theme'    => 'rss'));
            break;
        case 'detail':
            if (isset($eid)) {
                if (_SETTING_OPEN_NEW_WINDOW && !_SETTING_USE_POPUPS) {
                    $javascript = " onClick=\"opencal('$eid','$date'); return false;\"";
                    $link = "#";
                } else {
                    $link = pnModURL('PostCalendar', 'user', 'view', array(
                        'Date'     => $date,
                        'viewtype' => 'details',
                        'eid'      => $eid));
                }
            } else {
                $link = '';
            }
            break;
    }

    $link = DataUtil::formatForDisplay($link);
    $title = "";
    $labeltexts = array(
        'today'  => __('Jump to Today', $dom),
        'day'    => __('Day View', $dom),
        'week'   => __('Week View', $dom),
        'month'  => __('Month View', $dom),
        'year'   => __('Year View', $dom),
        'list'   => __('List View', $dom),
        'add'    => __('Submit New Event', $dom),
        'search' => __('Search', $dom),
        'print'  => __('Print View', $dom),
        'rss'    => __('RSS Feed', $dom));
    if ($full) {
        if ($navlink) {
            if (_SETTING_USENAVIMAGES) {
                $image_text = $labeltexts[$action];
                $image_src = ($viewtype == $action) ? $action . '_on.gif' : $action . '.gif';
                // version 1.3 compat
                // requires compat_layer = true in config.php
                // using <= 1.2.9 allows for 1.3.0-dev (since dev < 1.3.0)
                if (version_compare(pnConfigGetVar('Version_Num'), '1.2.9', '<=')) {
                    $img_plugin_name = 'pnimg';
                } else {
                    $img_plugin_name = 'img';
                }
                $img_function_name = 'smarty_function_' . $img_plugin_name;
                include_once $smarty->_get_plugin_filepath('function', $img_plugin_name);
                $pnimg_params = array(
                    'src'   => $image_src,
                    'alt'   => $image_text,
                    'title' => $image_text);
                if ($action == 'print') {
                    $pnimg_params['modname'] = 'core';
                    $pnimg_params['set']     = 'icons/small';
                    $pnimg_params['src']     = 'printer1.gif';
                }
                if ($action == 'rss') {
                    $pnimg_params['modname'] = 'PostCalendar';
                    $pnimg_params['src']     = 'feed.gif';
                }
                $display = $img_function_name($pnimg_params, $smarty);
                $class = 'postcalendar_nav_img';
                $title = $image_text;
            } else {
                $linkmap = array(
                    'today'  => __('Today', $dom),
                    'day'    => __('Day', $dom),
                    'week'   => __('Week', $dom),
                    'month'  => __('Month', $dom),
                    'year'   => __('Year', $dom),
                    'list'   => __('List', $dom),
                    'add'    => __('Add', $dom),
                    'search' => __('Search', $dom),
                    'print'  => __('Print', $dom),
                    'rss'    => __('RSS', $dom));
                $display = $linkmap[$action];
                $class = ($viewtype == $action) ? 'postcalendar_nav_text_selected' : 'postcalendar_nav_text';
                $title = $labeltexts[$action];
            }
        }
        // create string of remaining properties and values
        $props = "";
        if (!empty($args)) {
            foreach ($args as $prop => $val) {
                $props .= " $prop='$val'";
            }
        }
        if ($class) {
            $class = " class='$class'";
        }
        if ($title) {
            $title = " title='$title'";
        }
        $ret_val = "<a href='$link'" . $class . $title . $props . $javascript . ">$display</a>";
    } else {
        $ret_val = $link;
    }

    if (isset($assign)) {
        $smarty->assign($assign, $ret_val);
    } else {
        return $ret_val;
    }
}
