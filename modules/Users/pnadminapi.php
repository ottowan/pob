<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadminapi.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
*/

/**
 * users_adminapi_userexists()
 * Find whether the user exists. return true or false
 *
 * @param $args args['chng_user'] can be both username and userid
 * @return bool
 **/
function users_adminapi_userexists($args)
{
    $user = DBUtil::selectObjectByID('users', $args['chng_user'], 'uname');
    if (!$user) {
        $user = DBUtil::selectObjectByID('users', $args['chng_user'], 'uid');
    }

    return (boolean)$user;
}

/**
 * users_adminapi_getusergroups()
 * Get a list of usergroups
 *
 * @param $args
 * @return array of items
 **/
function users_adminapi_getusergroups($args)
{
    // Need read access to call this function
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return false;
    }

    // Get and display current groups
    $objArray = DBUtil::selectObjectArray('groups', '', 'name');

    if ($objArray === false) {
        LogUtil::registerError (_GETFAILED);
    }

    return $objArray;
}

/**
 * users_adminapi_findusers()
 * Find users
 *
 * @param $args
 * @return mixed array of items if succcessful, false otherwise
 **/
function users_adminapi_findusers($args)
{
    // Need read access to call this function
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return false;
    }

    pnModDBInfoLoad('Profile');
    $pntable    = pnDBGetTables();
    $userstable = $pntable['users'];
    $column     = $pntable['users_column'];

    // i'll finish seperate function if i have time
    /*if (isset($perpage)==0 or $perpage=="") {
        $perpage=500;
    }
    if (isset($startat)==0 or $startat=="") {
        $startat=0;
    }*/

    // Set query conditions (unless some one else sends a hardcoded one)
    if (!isset($args['condition']) || !$args['condition']) {
        $args['condition'] = '1=1';

        // process all of these in one loop
        $vars = array();
        $vars[] = 'uname';
        $vars[] = 'email';
        foreach ($vars as $var) {
            if (isset($args[$var]) && !empty($args[$var])) {
                $args['condition'] .= ' AND INSTR('.$column[$var].',"'.DataUtil::formatForStore($args[$var]).'")>0';
            }
        }

        // do the rest manually
        if (isset($args['ugroup']) && $args['ugroup']) {
            Loader::loadClass('UserUtil');
            $guids = UserUtil::getUsersForGroup ($args['ugroup']);
            if ($guids)
            {
                $args['condition'] .= " AND $column[uid] IN (";
                foreach ($guids as $uid)
                    $args['condition'] .= DataUtil::formatForStore($uid) . ',';

                $args['condition'] .= "0";
                $args['condition'] .= ")";
            }
        }
        if (isset($args['regdateafter']) && $args['regdateafter']) {
            $args['condition'] .= " AND ".$column['user_regdate'].">'".DataUtil::formatForStore($args['regdateafter'])."'";
        }
        if (isset($args['regdatebefore']) && $args['regdatebefore']) {
            $args['condition'] .= " AND ".$column['user_regdate']."<'".DataUtil::formatForStore($args['regdatebefore'])."'";
        }

        // Check for dynamic user data
        $dudRestrictions = array();
        $dudJoins = array();
        if (is_array($args['dynadata']))
        {
            $propTable = $pntable['user_property'];
            $propColumn = $pntable['user_property_column'];
            $dataTable = $pntable['user_data'];
            $dataColumn = $pntable['user_data_column'];

            $dynadata = $args['dynadata'];
            $duditems = pnModAPIFunc('Profile', 'user', 'getallactive');
            foreach ($duditems as $item)
            {
              if ($item['prop_label'] != '_YOURAVATAR')
              {
                if (!empty($dynadata[$item['prop_label']]))
                {
                    $value = DataUtil::formatForStore($dynadata[$item['prop_label']]);
                    // Hack: depends on selectExpandedObjectArray internal naming
                    $dudRestrictions[] = "INSTR(a.$dataColumn[uda_value], '$value') > 0";
                    $dudJoins[] = array('join_table' => 'user_data',
                                        'join_field' => 'uda_value',
                                        'object_field_name' => $item['prop_label'],
                                        'compare_field_table' => $column['uid'],
                                        'compare_field_join' => 'uda_uid');
                }
              }
            }
        }
    }
    $where    = "WHERE $args[condition] AND ".$column['uname']."!='Anonymous'";
    if (!empty($dudRestrictions))
    {
        $where .= " AND " . implode(' AND ', $dudRestrictions);
    }
    $orderby  = "ORDER BY ".$column['uid']." DESC";
    $objArray = DBUtil::selectExpandedObjectArray('users', $dudJoins, $where, $orderby); // currently no paging support

    if (!$objArray)
        $objArray = null;

    return $objArray;
}

/**
 * users_adminapi_saveuser()
 * Save User
 *
 * @param $args
 * @return bool true if successful, false otherwise
 **/
function users_adminapi_saveuser($args)
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)) {
        return false;
    }

    // Checking for necessary basics
    if (!isset($args['uid']) || empty($args['uid']) || !isset($args['uname']) || empty($args['uname']) ||
        !isset($args['email'])  || empty($args['email'])) {
        return LogUtil::registerError (_USERS_ERRORINREQUIREDFIELDS);
    }

    // load user language file
    pnModLangLoad('Users', 'user');

    $checkpass = false;
    if (isset($args['pass']) && !empty($args['pass'])) {
        $checkpass = true;
    }

    if ($checkpass) {
        if (isset($args['pass']) && isset($args['vpass']) && $args['pass'] !== $args['vpass']) {
            return LogUtil::registerError (_USERS_PASSDIFFERENT);
        }

        $pass  = $args['pass'];
        $vpass = $args['vpass'];

        $minpass = pnModGetVar('Users', 'minpass');
        if (empty($pass) || strlen($pass) < $minpass) {
            return LogUtil::registerError (pnML('_USERS_YOURPASSMUSTBETHISLONG', array('x' => $minpass)));
        }
        if (!empty($pass) && $pass) {
            $method = pnModGetVar('Users', 'hash_method', 'sha1');
            $hashmethodsarray = pnModAPIFunc('Users', 'user', 'gethashmethods');
            $args['pass'] = DataUtil::hash($pass, $method);
            $args['hash_method'] = $hashmethodsarray[$method];
        }
    } else {
        unset($args['pass']);
    }

    $dynadata = array();
    if ($args['dynadata']) {
        $dynadata = $args['dynadata'];

        $checkrequired = pnModAPIFunc('Profile', 'user', 'checkrequired',
                                      array('dynadata' => $dynadata));

        if ($checkrequired['result'] == true) {
            return LogUtil::registerError(_PROFILE_REQUIREDMISSING . ' ('.$checkrequired['translatedFieldsStr'].')');
        }
    }

    if (isset($dynadata['_UFAKEMAIL']) && !empty($dynadata['_UFAKEMAIL'])) {
        $femail = $dynadata['_UFAKEMAIL'];
        $dynadata['_UFAKEMAIL'] = preg_replace('/[^a-zA-Z0-9_@.-]/', '', $femail);
    }

    if (isset($dynadata['_YOURHOMEPAGE']) && !empty($dynadata['_YOURHOMEPAGE'])) {
        $url = $dynadata['_YOURHOMEPAGE'];

        $url = preg_replace('/[^a-zA-Z0-9_@.&#?;:\/-]/', '', $url);
        if (!eregi("^http://[\-\.0-9a-z]+", $url)) {
            $dynadata['_YOURHOMEPAGE'] = "http://" . $url; //$args['url'].$url;
        }
    }

    DBUtil::updateObject($args, 'users', '', 'uid');

    if (!empty($dynadata) && is_array($dynadata)) {
        while (list($key, $val) = each($dynadata)) {
            pnUserSetVar($key, $val, $args['uid']);
        }
    }

    // Fixing a high numitems to be sure to get all groups
    $groups = pnModAPIFunc('Groups', 'user', 'getall', array('numitems' => 1000));

    foreach($groups as $group) {
        if (in_array($group['gid'], $args['access_permissions'])) {
            // Check if the user is already in the group
            $useringroup = false;
            $usergroups  = pnModAPIFunc('Groups', 'user', 'getusergroups', array('uid' => $args['uid']));
            if ($usergroups) {
                foreach($usergroups as $usergroup) {
                    if ($group['gid'] == $usergroup['gid']) {
                        $useringroup = true;
                        break;
                    }
                }
            }
            // User is not in this group
            if ($useringroup == false) {
                pnModAPIFunc('Groups', 'admin', 'adduser', array('gid' => $group['gid'], 'uid' => $args['uid']));
            }
        } else {
            // We don't need to do a complex check, if the user is not in the group, the SQL will not return
            // an error anyway.
            pnModAPIFunc('Groups', 'admin', 'removeuser', array('gid' => $group['gid'], 'uid' => $args['uid']));
        }
    }

    // Let other modules know we have updated an item
    pnModCallHooks('item', 'update', $args['uid'], array('module' => 'Users'));

    return true;
}

/**
 * users_adminapi_deleteuser()
 *
 * @param $args[uid] int/array(int) one or many user IDs to delete
 * @return bool true if successful, false otherwise
 **/
function users_adminapi_deleteuser($args)
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_DELETE)) {
        return false;
    }

    if (!isset($args['uid']) || !(is_numeric($args['uid']) || is_array($args['uid']))) {
        return LogUtil::registerError('Illegal argument passed to deleteuser');
    }

    // ensure we always have an array
    if (!is_array($args['uid'])) {
        $args['uid'] = array (0 => $args['uid']);
    }

    foreach($args['uid'] as $id) {
        if (!DBUtil::deleteObjectByID('group_membership', $id, 'uid')) {
            return false;
        }

        if (!DBUtil::deleteObjectByID('users', $id, 'uid')) {
            return false;
        }

        if (!DBUtil::deleteObjectByID('user_data', $id, 'uda_uid')) {
            return false;
        }

        // Let other modules know we have deleted an item
        pnModCallHooks('item', 'delete', $id, array('module' => 'Users'));
    }

    return $args['uid'];
}

/**
 * users_adminapi_denyuser()
 *
 * @param $args
 * @return true if successful, false otherwise
 **/
function users_adminapi_deny($args)
{
    if (!isset($args['userid']) || !$args['userid']) {
        return false;
    }

    $res = DBUtil::deleteObjectByID('users_temp', $args['userid'], 'tid');

    return $res;
    //return (boolean)$res->Affected_Rows(); Currently not working
}

/**
 * users_adminapi_approveuser()
 *
 * @param $args
 * @return true if successful, false otherwise
 **/
function users_adminapi_approve($args)
{
    $false = false;

    if (!isset($args['userid']) || !$args['userid']) {
        return $false;
    }

    $user = DBUtil::selectObjectByID('users_temp', $args['userid'], 'tid');

    if (!$user) {
        return $user;
    }

    $user['vpass']     = $user['pass'];
    $user['dynadata']  = unserialize($user['dynamics']);
    $user['moderated'] = true;

    $insert = pnModAPIFunc('Users', 'user', 'finishnewuser', $user);

    if ($insert) {
        // $insert has uid, we remove it from the temp
        $result = pnModAPIFunc('Users', 'admin', 'deny', array('userid' => $args['userid']));
    } else {
        $result = $false;
    }

    return $result;
}

/**
 * users_adminapi_mailuser()
 *
 * @param $args
 * @return true if successful, false otherwise
 **/
function users_adminapi_mailuser($args)
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_ADMIN)) {
        return false;
    }

    if (!isset($args['uid']) || !$args['uid']) {
        return false;
    }

    // ensure we always have an array
    if (!is_array($args['uid'])) {
        $args['uid'] = array (0 => $args['uid']);
    }

    // TODO: we're putting multiple RCs into a single var ???
    foreach($args['uid'] as $userid) {
        $rc = (boolean)pnMail($args['rpemail'], $args['subject'], $args['message'],
                              "From: \"".$args['from']."\" <".$args['rpemail'].">\nBcc: ".
                              pnUserGetVar('email', $userid)."\nX-Mailer: PHP/" . phpversion());
    }

    return $rc;
}

/**
 * get all example items
 *
 * @param    int     $args['starnum']    (optional) first item to return
 * @param    int     $args['numitems']   (optional) number if items to return
 * @return   array   array of items, or false on failure
 */
function users_adminapi_getallpendings($args)
{
    // Optional arguments.
    $startnum = (isset($args['startnum']) && is_numeric($args['startnum'])) ? $args['startnum'] : 1;
    $numitems = (isset($args['numitems']) && is_numeric($args['numitems'])) ? $args['numitems'] : -1;
    unset($args);

    $items = array();

    // Security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_OVERVIEW)) {
        return $items;
    }

    $pntable = pnDBGetTables();
    $userscolumn = $pntable['users_temp_column'];
    $orderby = "ORDER BY $userscolumn[tid]";
    $permFilter = array();
    // corresponding filter permission to filter anonymous in admin view:
    // Administrators | Users:: | Anonymous:: | None
    $permFilter[] = array('realm' => 0,
                          'component_left'   => 'Users',
                          'component_middle' => '',
                          'component_right'  => '',
                          'instance_left'    => 'uname',
                          'instance_middle'  => '',
                          'instance_right'   => '',
                          'level'            => ACCESS_EDIT);
    $result = DBUtil::selectObjectArray('users_temp', '', $orderby, $startnum-1, $numitems, '', $permFilter);

    if ($result === false) {
        LogUtil::registerError (_GETFAILED);
    }

    return $result;
}

function users_adminapi_getapplication($args)
{
    if (!isset($args['userid']) || !$args['userid']) {
        return false;
    }

    $item = DBUtil::selectObjectByID('users_temp', $args['userid'], 'tid');

    if ($item === false) {
        LogUtil::registerError (_GETFAILED);
    }

    return $item;
}

/**
 * users_adminapi_countpending()
 *
 * @param $args
 * @return nb of pending applications, false otherwise
 **/
function users_adminapi_countpending()
{
    return DBUtil::selectObjectCount('users_temp');
}

/**
 * get available admin panel links
 *
 * @author Mark West
 * @return array array of admin links
 */
function Users_adminapi_getlinks()
{
    $links = array();

    pnModLangLoad('Users', 'admin');

    if (SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        $links[] = array('url' => pnModURL('Users', 'admin', 'view'), 'text' => _USERS_VIEWUSERS);
    }
    if (SecurityUtil::checkPermission('Users::', '::', ACCESS_ADD)) {
        $pending = pnMODAPIFunc('Users', 'admin', 'countpending');
        if ($pending) {
            $links[] = array('url' => pnModURL('Users', 'admin', 'viewapplications'), 'text' => _USERS_PENDINGAPPLICATIONS . ' ( '.DataUtil::formatForDisplay($pending).' )');
        }
        $links[] = array('url' => pnModURL('Users', 'admin', 'new'), 'text' => _USERS_CREATEUSER);
    }
    if (SecurityUtil::checkPermission('Users::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('Users', 'admin', 'modifyconfig'), 'text' => _MODIFYUSERSCONFIG );
    }
    if (SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        $links[] = array('url' => pnModURL('Users', 'admin', 'search'), 'text' => _SEARCHUSERS );
    }
    if (pnModAvailable('Profile')) {
        if (SecurityUtil::checkPermission('Profile::', '::', ACCESS_READ)) {
            if (pnModGetName() == 'Users') {
                $links[] = array('url' => 'javascript:showdynamicsmenu()', 'text' => _USERS_DYNAMICDATA );
            } else {
                $links[] = array('url' => pnModURL('Profile', 'admin', 'main'), 'text' => _USERS_DYNAMICDATA );
            }
        }
    }

    return $links;
}
