<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnsearchapi.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
*/

/**
 * Search plugin info
 **/
function users_searchapi_info()
{
    pnModLangLoad('Users', 'user');
    return array('title' => 'Users',
                 'functions' => array('Users' => 'search'));
}

/**
 * Search form component
 **/
function users_searchapi_options($args)
{
    if (SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender = pnRender::getInstance('Users');
        $pnRender->assign('active',(isset($args['active'])&&isset($args['active']['Users']))||(!isset($args['active'])));
        return $pnRender->fetch('users_search_options.htm');
    }

    return '';
}

function users_searchapi_search($args)
{
    // Security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return $items;
    }

    // get the db and table info
    pnModDBInfoLoad('Profile', 'Profile');
    pnModDBInfoLoad('Users', 'Users');
    pnModLangLoad('Profile', 'user');
    $dbconn  = pnDBGetConn(true);
    $pntable = pnDBGetTables();
    $userstable  = $pntable['users'];
    $userscolumn = $pntable['users_column'];
    $datacolumn  = $pntable['user_data_column'];
    $propcolumn  = $pntable['user_property_column'];
    $searchTable = $pntable['search_result'];
    $searchColumn = $pntable['search_result_column'];

    // Select basic user data and join _UREALNAME property into the result
    $sql = "SELECT     $datacolumn[uda_value] as name,
                       $userscolumn[uname] as uname,
                       $userscolumn[uid] as uid,
                       $userscolumn[user_regdate] as user_regdate
            FROM       $pntable[users]
            LEFT JOIN  $pntable[user_property]
                       ON $propcolumn[prop_label] = '_UREALNAME'
            LEFT JOIN  $pntable[user_data]
                       ON $datacolumn[uda_propid] = $propcolumn[prop_id] AND $datacolumn[uda_uid] = $userscolumn[uid] ";

    // form the where clause
    $where = "WHERE $userscolumn[activated] = 1 AND ";
    $where .= search_construct_where($args, array($userscolumn['uname'], $datacolumn['uda_value']));

    $sql = $sql . $where;
    $result = DBUtil::executeSQL($sql);
    if (!$result) {
        return LogUtil::registerError(_GETFAILED);
    }

    $sessionId = session_id();

    $insertSql = "INSERT INTO $searchTable
                      ($searchColumn[title],
                       $searchColumn[text],
                       $searchColumn[extra],
                       $searchColumn[module],
                       $searchColumn[created],
                       $searchColumn[session])
                    VALUES ";

    // process the result set into an array of records
    for (; !$result->EOF; $result->MoveNext()) {
        $user = $result->GetRowAssoc(2);
        if ($user['uid'] != 1 && SecurityUtil::checkPermission('Users::', "$user[uname]::$user[uid]", ACCESS_READ)) {
            $name = !empty($user['name']) ? $user['name'] : _USERS_SEARCHNOEXTRAINFOVIEWPROFILE;
            $sql = $insertSql . '('
                   . '\'' . _USERS_SEARCH . ': ' . DataUtil::formatForStore($user['uname']) . '\', '
                   . '\'' . DataUtil::formatForStore(_UREALNAME . ': ' . $user['name']) . '\', '
                   . '\'' . DataUtil::formatForStore($user['uid']) . '\', '
                   . '\'' . 'Users' . '\', '
                   . '\'' . DataUtil::formatForStore($user['user_regdate']) . '\', '
                   . '\'' . DataUtil::formatForStore($sessionId) . '\')';
            $insertResult = DBUtil::executeSQL($sql);
            if (!$insertResult) {
                return LogUtil::registerError (_GETFAILED);
            }
        }
    }

    return true;
}


/**
 * Do last minute access checking and assign URL to items
 *
 * Access checking is ignored since access check has
 * already been done. But we do add a URL to the found user
 */
function users_searchapi_search_check(&$args)
{
    $datarow = &$args['datarow'];
    $userId = $datarow['extra'];

    $datarow['url'] = pnModUrl('Profile', 'user', 'view', array('uid' => $userId));

    return true;
}

