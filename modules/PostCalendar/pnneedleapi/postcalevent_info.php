<?php
/**
 * @package     PostCalendar
 * @author      $Author: teb $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pnneedleapi/postcalevent_info.php $
 * @version     $Id: postcalevent_info.php 475 2010-01-06 21:18:28Z teb $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * event needle info
 * @param none
 * @return array()
 */
function postcalendar_needleapi_postcalevent_info()
{
    $info = array(
        'module'        => 'PostCalendar', // module name
        'info'          => 'POSTCALEVENT-{eventid-displaytype}', // possible needles
        'inspect'       => true,
        //'needle'        => array('http://', 'https://', 'ftp://', 'mailto://'),
        //'function'      => 'http',
        //'casesensitive' => false,
    );
    return $info;
}