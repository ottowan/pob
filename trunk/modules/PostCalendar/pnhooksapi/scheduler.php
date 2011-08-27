<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnhooksapi/scheduler.php $
 * @version     $Id: scheduler.php 551 2010-02-11 01:24:31Z craigh $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * convert scheduled events status to APPROVED on their eventDate for hooked news events
 *
 * @author  Craig Heydenburg
 * @return  null
 * @access  public
 */
function postcalendar_hooksapi_scheduler($args)
{
    $today = DateUtil::getDatetime(null, '%Y-%m-%d');
    $time  = DateUtil::getDatetime(null, '%H:%M:%S');
    $where = "WHERE pc_hooked_modulename = 'news' 
              AND pc_eventstatus = -1 
              AND pc_eventDate <= '$today' 
              AND pc_startTime <= '$time'";
    $object['eventstatus'] = 1;
    DBUtil::updateObject($object, 'postcalendar_events', $where, 'eid');
    return;
}