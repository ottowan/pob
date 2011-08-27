<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnaccountapi.php $
 * @version     $Id: pnaccountapi.php 750 2010-07-27 11:52:56Z craigh $
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Return an array of items to show in the your account panel
 *
 * @return   array   array of items, or false on failure
 */
function PostCalendar_accountapi_getall($args)
{
    $dom = ZLanguage::getModuleDomain('PostCalendar');

    $items = array();
    // show link for users only
    if (!pnUserLoggedIn()) {
        // not logged in
        return $items;
    }
    if (SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_ADD)) {
        $items['1'] = array(
            'url' => pnModURL('PostCalendar', 'event', 'new'),
            'title' => __('Submit Event', $dom),
            'icon' => 'admin.png');
    }
    if (SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_DELETE)) {
        $items['2'] = array(
            'url' => pnModURL('PostCalendar', 'admin', 'listqueued'),
            'title' => __('Administrate Events', $dom),
            'icon' => 'admin.png');
    }
    // Return the items
    return $items;
}
