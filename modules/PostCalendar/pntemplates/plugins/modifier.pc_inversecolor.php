<?php
/**
 * @package     PostCalendar
 * @author      $Author: craigh $
 * @link        $HeadURL: https://code.zikula.org/svn/soundwebdevelopment/tags/PostCalendar620/pntemplates/plugins/modifier.pc_inversecolor.php $
 * @version     $Id: modifier.pc_inversecolor.php 472 2010-01-06 01:34:38Z craigh $
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
function smarty_modifier_pc_inversecolor($color)
{
    if (empty($color)) {
        return;
    }
    return pnModAPIFunc('PostCalendar', 'event', 'color_inverse', $color);
}