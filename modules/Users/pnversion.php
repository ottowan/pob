<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnversion.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
*/

$modversion['name']           = 'Users';
$modversion['oldnames']       = array('User');
$modversion['displayname']    = _USERS_DISPLAYNAME;
$modversion['description']    = _USERS_MODDESCRIPTION;
$modversion['version']        = '1.8';
$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/help.txt';
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = 1;
$modversion['author']         = 'Xiaoyu Huang, Drak';
$modversion['contact']        = 'class007@sina.com, drak@zikula.org';
$modversion['securityschema'] = array('Users::user'         => 'Uname::User ID',
                                      'Users::newuser'      => '::',
                                      'Users::lostpassword' => '::',
                                      'Users::youraccount'  => '::',
                                      'Users::'             => '::');
