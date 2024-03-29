<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: online.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
 */

/**
 * return the block info
*/
function users_onlineblock_info()
{
    return array(
    'module'         => 'Users',
    'text_type'      => 'Who\'s online',
    'text_type_long' => 'Online block',
    'allow_multiple' => false,
    'form_content'   => false,
    'form_refresh'   => false,
    'show_preview'   => true
    );
}

/**
 * initialise the block
 *
 * Adds the blocks security schema to the PN environment
*/
function users_onlineblock_init()
{
    // Security
    pnSecAddSchema('Onlineblock::', 'Block title::');
}

/**
 * Display the block
 *
 * Display the output of the online block
 *
 * @todo move sql queries to calls to relevant API's
*/
function users_onlineblock_display($row)
{
    if (!SecurityUtil::checkPermission('Onlineblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    // create the output object
    $pnr = pnRender::getInstance('Users');

    // Here we use the user id as the cache id since the block shows user based
    // information; username and number of private messages
    $pnr->cache_id = pnUserGetVar('uid');

    // check out if the contents are cached.
    // If this is the case, we do not need to make DB queries.
    if ($pnr->is_cached('users_block_online.htm')) {
        $row['content'] = $pnr->fetch('users_block_online.htm');
        return pnBlockThemeBlock($row);
    }

    $pntable = pnDBGetTables();

    $sessioninfocolumn = $pntable['session_info_column'];
    $activetime = adodb_strftime('%Y-%m-%d %H:%M:%S', time() - (pnConfigGetVar('secinactivemins') * 60));

    $where = "WHERE $sessioninfocolumn[lastused] > '$activetime' AND $sessioninfocolumn[uid] > 0";
    $numusers = DBUtil::selectObjectCount('session_info', $where, 'uid', true);

    $where = "WHERE $sessioninfocolumn[lastused] > '$activetime' AND $sessioninfocolumn[uid] = '0'";
    $numguests = DBUtil::selectObjectCount('session_info', $where, 'ipaddr', true);

    $pnr->assign('registerallowed', pnModGetVar('Users', 'reg_allowreg'));
    $pnr->assign('loggedin', pnUserLoggedIn());
    $pnr->assign('userscount', $numusers );
    $pnr->assign('guestcount', $numguests );

    $numrows = 0;
    $unreadrows = 0;
    if (pnUserLoggedIn()) {
        $pnr->assign('username', pnUserGetVar('uname'));
        // check if pnMessages/InterCom are available and add the necessary info
        if (pnModAvailable('pnMessages')) {
            $pnr->assign('msgmodule', 'pnMessages');
            $pnr->assign('messages', pnModAPIFunc('pnMessages', 'user', 'getmessagecount'));
        } else if (pnModAvailable('InterCom')) {
            $pnr->assign('msgmodule', 'InterCom');
            $pnr->assign('messages', pnModAPIFunc('InterCom', 'user', 'getmessagecount'));
        } else {
            $pnr->assign('msgmodule', '');
            $pnr->assign('messages', array());
        }
    }

    $row['content'] = $pnr->fetch('users_block_online.htm');
    return pnBlockThemeBlock($row);
}
