<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnaccountapi.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Mark West
 * @package Zikula_System_Modules
 * @subpackage Users
 */

/**
 * Return an array of items to show in the your account panel
 *
 * @return   array   array of items, or false on failure
 */
function Users_accountapi_getall($args)
{
    // load user language file
    pnModLangLoad('Users', 'user');

    $items = array();

    // check if the users block exists
    $blocks = pnModAPIFunc('Blocks', 'user', 'getall');
    $mid = pnModGetIDFromName('Users');
    $found = false;
    foreach ($blocks as $block) {
        if ($block['mid'] == $mid && $block['bkey'] == 'user') {
            $found = true;
            break;
        }
    }

    if ($found) {
        $items[] = array('url' => pnModURL('Users', 'user', 'usersblock'),
                         'module' => 'core',
                         'set' => 'icons/large',
                         'title' => _USERS_USERSBLOCK,
                         'icon' => 'folder_home.gif');
    }
    $items[] = array('url' => pnModURL('Users', 'user', 'logout'),
                     'module' => 'core',
                     'set' => 'icons/large',
                     'title' => _LOGOUT,
                     'icon' => 'exit.gif');

    // Return the items
    return $items;
}
