<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadmin.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Xiaoyu Huang
 * @package Zikula_System_Modules
 * @subpackage Users
 */

/**
 * users_admin_main()
 * Redirects users to the "view" page
 *
 * @return bool true if successful false otherwise
 */
function users_admin_main()
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_ADMIN)){
        return LogUtil::registerPermissionError();
    }

    return pnRedirect(pnModURL('Users', 'admin', 'view'));
}

/**
 * form to add new item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to create a new module item
 *
 * @author       The Zikula Development Team
 * @return       output       The main module admin page.
 */
function users_admin_new()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender = pnRender::getInstance('Users', false);

    // load user language file
    pnModLangLoad('Users',   'user');
    pnModLangLoad('Profile', 'user');

    // Assign Users setting
    $modvars = pnModGetVar('Users');
    $pnRender->assign($modvars);

    if ($modvars['reg_optitems']) {
        $pnRender->assign('optitems', pnModAPIFunc('Profile', 'user', 'getallactive', array()));
    }

    // Return the output that has been generated by this function
    return $pnRender->fetch('users_admin_new.htm');
}

/**
 * Create user
 *
 * @param  $args
 * @return mixed true if successful, string otherwise
 */
function users_admin_create()
{
    // check permisisons
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    // load user language file
    pnModLangLoad('Users', 'user');

    // get arugments
    $uname = FormUtil::getPassedValue('add_uname', null, 'POST');
    $email = FormUtil::getPassedValue('add_email', null, 'POST');
    $pass  = FormUtil::getPassedValue('add_pass', null, 'POST');
    $vpass = FormUtil::getPassedValue('add_vpass', null, 'POST');

    $optionals = false;
    $dynadata = array();

    if (pnModGetVar('Users', 'reg_optitems') == 1) {
        $dynadata  = FormUtil::getPassedValue ('dynadata');
        $optionals = true;
        pnModLangLoad('Profile', 'user');
    }

    // call the API
    $checkuser = pnModAPIFunc('Users', 'user', 'checkuser',
                              array('uname'        => $uname,
                                    'email'        => $email,
                                    'agreetoterms' => 1));

    // if errorcode != 1 then return error msgs
    if ($checkuser != 1) {
        switch($checkuser){
        case -1:
            LogUtil::registerError (_MODULENOAUTH);
            break;
        case 2:
            LogUtil::registerError (_USERS_INVALIDEMAIL);
            break;
        case 3:
            LogUtil::registerError (_USERS_ERRORMUSTAGREE);
            break;
        case 4:
            LogUtil::registerError (_USERS_USERNAMEINVALID);
            break;
        case 5:
            LogUtil::registerError (_USERS_USERNAMETOOLONG);
            break;
        case 6:
            LogUtil::registerError (_USERS_USERNAMERESERVED);
            break;
        case 7:
            LogUtil::registerError (_USERS_USERNAMENOSPACES);
            break;
        case 8:
            LogUtil::registerError (_USERS_USERNAMETAKEN);
            break;
        case 9:
            LogUtil::registerError (_USERS_EMAILREGISTERED);
            break;
        default:
            LogUtil::registerError (_MODULENOAUTH);
        } // switch
        return pnRedirect(pnModURL('Users', 'admin', 'main'));
    }

    if ($optionals) {
        // Check for required fields - The API function is called.
        $checkrequired = pnModAPIFunc('Profile', 'user', 'checkrequired',
                                      array('dynadata' => $dynadata));

        if ($checkrequired['result'] == true) {
            LogUtil::registerError(_PROFILE_REQUIREDMISSING . ' ('.$checkrequired['translatedFieldsStr'].')');
            return pnRedirect(pnModURL('Users','admin','new'));
        }
    }

    $minpass = pnModGetVar('Users', 'minpass');

    if (isset($message)) {
        LogUtil::registerError ($message);
        return pnRedirect(pnModURL('Users','admin','new'));
    }

    if (empty($pass)) {
        LogUtil::registerError (_USERS_NOPASS);
        return pnRedirect(pnModURL('Users', 'admin', 'main'));
    } elseif ((isset($pass)) && ("$pass" != "$vpass")) {
        LogUtil::registerError (_USERS_PASSDIFFERENT);
        return pnRedirect(pnModURL('Users', 'admin', 'main'));
    } elseif (($pass != '') && (strlen($pass) < $minpass)) {
        LogUtil::registerError (pnML('_USERS_YOURPASSMUSTBETHISLONG', array('x' => $minpass)));
        return pnRedirect(pnModURL('Users', 'admin', 'main'));
    }

    $registered = pnModAPIFunc('Users', 'user', 'finishnewuser',
                               array('isadmin'       => 1,
                                     'name'          => 'blank',
                                     'uname'         => $uname,
                                     'pass'          => $pass,
                                     'email'         => $email,
                                     'user_avatar'   => 'blank.gif',
                                     'user_regdate'  => date("Y-m-d H:i:s", time()),
                                     'moderated'     => false,
                                     'dynadata'      => $dynadata,
                                     'optionals'     => $optionals));


    if ($registered) {
       LogUtil::registerStatus (pnML('_CREATEITEMSUCCEDED', array('i' => _USERS_USER)));
    } else {
        LogUtil::registerError (_CREATEFAILED);
    }

    return pnRedirect(pnModURL('Users', 'admin', 'main'));
}

/**
 * view items
 *
 * This function shows all items and lists the administration
 * options.
 *
 * @author       The Zikula Development Team
 * @param        startnum     The number of the first item to show
 * @return       output       The main module admin page
 */
function users_admin_view()
{
    // Get parameters from whatever input we need.
    $startnum = FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : null, 'GET');
    $letter = FormUtil::getPassedValue('letter', isset($args['letter']) ? $args['letter'] : null, 'GET');

    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Users', false);

    // we need this value multiple times, so we keep it
    $itemsperpage = pnModGetVar('Users', 'itemsperpage');

    // Get all users
    $items = pnModAPIFunc('Users', 'user', 'getall',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage,
                                'letter' => $letter));

    // Loop through each returned item adding in the options that the user has over
    // each item based on the permissions the user has.
    foreach ($items as $key => $item) {
        $options = array();
        if (SecurityUtil::checkPermission('Users::', "$item[uname]::$item[uid]", ACCESS_READ) && $item['uid'] != 1) {
            // Options for the item.
            if (SecurityUtil::checkPermission('Users::', "$item[uname]::$item[uid]", ACCESS_EDIT)) {
                $options[] = array('url'   => pnModURL('Users', 'admin', 'modify', array('userid' => $item['uid'])),
                                   'title' => _EDIT,
                                   'image' => 'xedit.gif');
                if (SecurityUtil::checkPermission('Users::', "$item[uname]::$item[uid]", ACCESS_DELETE)) {
                    $options[] = array('url'   => pnModURL('Users', 'admin', 'deleteusers', array('userid' => $item['uid'])),
                                       'title' => _DELETE,
                                       'image' => '14_layer_deletelayer.gif');
                }
            }
        }
        // Add the calculated menu options to the item array
        $items[$key]['options'] = $options;
    }

    // Assign the items to the template
    $pnRender->assign('usersitems', $items);

    // assign the values for the smarty plugin to produce a pager in case of there
    // being many items to display.
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('Users', 'user', 'countitems', array('letter' => $letter)),
                                     'itemsperpage' => $itemsperpage));

    // Return the output that has been generated by this function
    return $pnRender->fetch('users_admin_view.htm');
}

/**
 * view items
 *
 * This function shows all items and lists the administration
 * options.
 *
 * @author       The Zikula Development Team
 * @param        startnum     The number of the first item to show
 * @return       output       The main module admin page
 */
function users_admin_viewapplications()
{
    // security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // Get parameters from whatever input we need.
    $startnum = FormUtil::getPassedValue ('startnum');

    // Create output object
    $pnRender = pnRender::getInstance('Users', false);

    // we need this value multiple times, so we keep it
    $itemsperpage = pnModGetVar('Users', 'itemsperpage');

    // The user API function is called.
    $items = pnModAPIFunc('Users', 'admin', 'getallpendings',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage));

    // Loop through each returned item adding in the options that the user has over
    // each item based on the permissions the user has.
    foreach ($items as $key => $item) {
        if (SecurityUtil::checkPermission('Users::', "$item[uname]::$item[tid]", ACCESS_READ)) {
            // Options for the item.
            $options = array();
            if (pnModGetVar('Users', 'reg_optitems')) {
                $options[] = array('url'   => pnModURL('Users', 'admin', 'viewtempuserinfo', array('userid' => $item['tid'])),
                                   'imgfile' => 'list.gif',
                                   'title' => _DETAILS);
            }
            if (SecurityUtil::checkPermission('Users::', "$item[uname]::$item[tid]", ACCESS_ADD)) {
                $options[] = array('url'   => pnModURL('Users', 'admin', 'processusers', array('userid' => $item['tid'], 'op' => 'approve')),
                                   'imgfile' => 'add_user.gif',
                                   'title' => _ADD);
                if (SecurityUtil::checkPermission('Users::', "$item[uname]::$item[tid]", ACCESS_DELETE)) {
                    $options[] = array('url'   => pnModURL('Users', 'admin', 'processusers', array('userid' => $item['tid'], 'op' => 'deny')),
                                       'imgfile' => 'delete_user.gif',
                                       'title' => _DELETE);
                }
            }

            // Add the calculated menu options to the item array
            $items[$key]['options'] = $options;
        }
    }

    // Assign the items to the template
    $pnRender->assign('usersitems', $items);

    // assign the values for the smarty plugin to produce a pager in case of there
    // being many items to display.
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('Users', 'admin', 'countpending'),
                                     'itemsperpage' => $itemsperpage));

    // Return the output that has been generated by this function
    return $pnRender->fetch('users_admin_viewapplications.htm');
}

/**
 * users_admin_viewtempuserinfo()
 * Shows the information for the temporary user
 *
 * @param  $args
 * @return string HTML string
 */
function users_admin_viewtempuserinfo()
{
    // security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    // Get parameters from whatever input we need.
    $userid = FormUtil::getPassedValue('userid', null, 'GET');

    if (empty($userid) || !is_numeric($userid)) {
        return LogUtil::registerError(_MODARGSERROR);
    }

    // Create output object
    $pnRender = pnRender::getInstance('Users', false);

    $tempuser = pnModAPIFunc('Users', 'admin', 'getapplication', array('userid' => $userid));

    $userinfo = array();

    $dynamics = unserialize($tempuser['dynamics']);

    $userinfo = array_merge($tempuser, $dynamics);
    //unset($tempuserinfo['dynamics']);

    $items = pnModAPIFunc('Profile', 'user', 'getall');
    // Removing password from the list
    unset($items['_PASSWORD']);

    foreach($items as $field) {

        $alias = pnModAPIFunc('Profile', 'user', 'aliasing', array('label' => $field['prop_label']));

        if (isset($userinfo[$field['prop_label']]) && $alias != $field['prop_label']) {
            $userinfo[$alias] = $userinfo[$field['prop_label']];
        } elseif (isset($userinfo[$alias]) && $alias != $field['prop_label']) {
            $userinfo[$field['prop_label']] = $userinfo[$alias];
        }

        if ($field['prop_displaytype'] > 6) {
            $pnRender->assign($alias, unserialize($userinfo[$alias]));
        } else if (isset($userinfo[$alias])) {
            //$pnRender->assign($alias, @unserialize($userinfo[$alias]));
            $pnRender->assign($alias, $userinfo[$alias]);
        }

    }

    pnModLangLoad('Profile', 'user');

    $pnRender->assign('uname',    $userinfo['uname']);
    $pnRender->assign('userid',   $userid);
    $pnRender->assign('fields',   $items);
    $pnRender->assign('userinfo', $userinfo);

    return $pnRender->fetch('users_admin_viewtempuserdetails.htm');

}

/**
 * user_admin_view()
 * Shows the search box for Edit/Delete
 * Shows Add User Dialog
 *
 * @param  $args
 * @return string HTML string
 */
function users_admin_search($args)
{
    // security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)){
        return LogUtil::registerPermissionError();
    }

    // create output object
    $pnRender = pnRender::getInstance('Users', false);

    // load the profile language file
    pnModLangLoad('Profile', 'user');

    // get group items
    // TODO: move to a call to the groups module
    $groups = pnModAPIFunc('Users', 'admin', 'getusergroups',
                          array());
    $pnRender->assign('groups', $groups);
    $duditems = pnModAPIFunc('Profile', 'user', 'getallactive');
    $pnRender->assign('duditems', $duditems);

    return $pnRender->fetch('users_admin_search.htm');
}

/**
 * users_admin_listusers()
 * list users
 *
 * @param  $args
 * @return string HTML string
 */
function users_admin_listusers($args)
{
    $uname         = FormUtil::getPassedValue('uname', null, 'POST');
    $rname         = FormUtil::getPassedValue('rname', null, 'POST');
    $ugroup        = FormUtil::getPassedValue('ugroup', null, 'POST');
    $email         = FormUtil::getPassedValue('email', null, 'POST');
    $homepage      = FormUtil::getPassedValue('homepage', null, 'POST');
    $icq           = FormUtil::getPassedValue('icq', null, 'POST');
    $msn           = FormUtil::getPassedValue('msn', null, 'POST');
    $aim           = FormUtil::getPassedValue('aim', null, 'POST');
    $yim           = FormUtil::getPassedValue('yim', null, 'POST');
    $signature     = FormUtil::getPassedValue('signature', null, 'POST');
    $regdateafter  = FormUtil::getPassedValue('regdateafter', null, 'POST');
    $regdatebefore = FormUtil::getPassedValue('regdatebefore', null, 'POST');
    $dynadata      = FormUtil::getPassedValue('dynadata', null, 'POST');

    // call the api
    $items = pnModAPIFunc('Users', 'admin', 'findusers',
                          array('uname'         => $uname,
                                'email'         => $email,
                                'ugroup'        => $ugroup,
                                'regdateafter'  => $regdateafter,
                                'regdatebefore' => $regdatebefore,
                                'dynadata'      => $dynadata)
                         );
    if (!$items) {
        LogUtil::registerError(pnML('_NOFOUND', array('i' => _USERS_USERS)), 404, pnModURL('Users', 'admin', 'search'));
    }

    // load the profile module language file
    pnModLangLoad('Profile', 'user');

    // create output object
    $pnRender = pnRender::getInstance('Users', false);

    // assign the matching results
    $pnRender->assign('items', $items);

    return $pnRender->fetch('users_admin_listusers.htm');
}

/**
 * users_admin_processusers()
 * Edit, Delete and Mail Users
 *
 * @param  $args
 * @return mixed true successful, false or string otherwise
 */
function users_admin_processusers($args)
{
    // security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)){
        return LogUtil::registerPermissionError();
    }

    // get the arguments from our input
    $op     = FormUtil::getPassedValue('op', null, 'GETPOST');
    $do     = FormUtil::getPassedValue('do', null, 'POST');
    $userid = FormUtil::getPassedValue('userid', null, 'POST');

    // create the output object
    $pnRender = pnRender::getInstance('Users', false);

    if ($op == 'edit' && !empty($userid)){
        if ($do != 'yes'){
            return pnRedirect(pnModURL('Users', 'admin', 'modify', array('userid' => $userid)));
        } else {
            $uname              = FormUtil::getPassedValue('uname', null, 'POST');
            $email              = FormUtil::getPassedValue('email', null, 'POST');
            $activated          = FormUtil::getPassedValue('activated', null, 'POST');
            $pass               = FormUtil::getPassedValue('pass', null, 'POST');
            $vpass              = FormUtil::getPassedValue('vpass', null, 'POST');
            $theme              = FormUtil::getPassedValue('theme', null, 'POST');
            $access_permissions = FormUtil::getPassedValue('access_permissions', null, 'POST');
            $dynadata           = FormUtil::getPassedValue('dynadata', null, 'POST');

            $return = pnModAPIFunc('Users', 'admin', 'saveuser', array('uid'                => $userid,
                                                                       'uname'              => $uname,
                                                                       'email'              => $email,
                                                                       'activated'          => $activated,
                                                                       'pass'               => $pass,
                                                                       'vpass'              => $vpass,
                                                                       'theme'              => $theme,
                                                                       'dynadata'           => $dynadata,
                                                                       'access_permissions' => $access_permissions));

            if ($return == true) {
                LogUtil::registerStatus (pnML('_UPDATEITEMSUCCEDED', array('i' => _USERS_USER)));
                return pnRedirect(pnModUrl('Users', 'admin', 'main'));
            } else {
                return false;
            }
        }
    } elseif ($op == 'delete' && !empty($userid)) {
        $userid = FormUtil::getPassedValue('userid', null, 'POST');
        if ($do != 'yes'){
            return pnRedirect(pnModURL('Users', 'admin', 'deleteusers', array('userid' => $userid)));
        } else {
            $return = pnModAPIFunc('Users', 'admin', 'deleteuser', array('uid' => $userid));

            if ($return == true) {
                LogUtil::registerStatus (pnML('_DELETEITEMSUCCEDED', array('i' => _USERS_USER)));
                return pnRedirect(pnModUrl('Users', 'admin', 'main'));
            } else {
                return false;
            }
        }
    } elseif ($op == 'mail' && !empty($userid)) {
        $userid = FormUtil::getPassedValue('userid', null, 'POST');
        if ($do != 'yes'){
            return pnRedirect(pnModURL('Users', 'admin', 'mailusers', array('userid' => $userid)));
        } else {
            $userid  = FormUtil::getPassedValue('userid', null, 'POST');
            $from    = FormUtil::getPassedValue('from', null, 'POST');
            $rpemail = FormUtil::getPassedValue('rpemail', null, 'POST');
            $subject = FormUtil::getPassedValue('subject', null, 'POST');
            $message = FormUtil::getPassedValue('message', null, 'POST');

            $return = pnModAPIFunc('Users', 'admin', 'mailuser',
                                   array('uid'     => $userid,
                                         'from'    => $from,
                                         'rpemail' => $rpemail,
                                         'subject' => $subject,
                                         'message' => $message
                                        ));

            if ($return == true) {
                LogUtil::registerStatus (_USERS_MAILSENT);
               return  pnRedirect(pnModUrl('Users', 'admin', 'main'));
            } else {
                LogUtil::registerError (_USERS_MAILSENTFAILED);
                return pnRedirect(pnModUrl('Users', 'admin', 'main'));
            }
        }
    } elseif ($op == 'approve' || $op == 'deny') {
        $tag = FormUtil::getPassedValue ('tag');

        if (empty($tag)) {
            $userid = FormUtil::getPassedValue('userid', null, 'GET');

            $pnRender->caching = false;

            $item = pnModAPIFunc('Users', 'admin', 'getapplication', array('userid' => $userid));

            if (!$item) {
                LogUtil::registerError (_USERS_NOUSERINFOFOUND);
                return pnRedirect(pnModUrl('Users', 'admin', 'main'));
            }

            $pnRender->assign('action', $op);
            $pnRender->assign('userid', $userid);
            $pnRender->assign('item',   $item);

            return $pnRender->fetch('users_admin_pendingaction.htm');

        } else {

            $userid = FormUtil::getPassedValue ('userid');
            $action = FormUtil::getPassedValue ('action');

            $return = pnModAPIFunc('Users', 'admin', $action, array('userid' => $userid));

            if ($return == true) {
                if ($op == 'approve') {
                    LogUtil::registerStatus (pnML('_CREATEITEMSUCCEDED', array('i' => _USERS_USER)));
                } else {
                    LogUtil::registerStatus (pnML('_DELETEITEMSUCCEDED', array('i' => _USERS_USER)));
                }
            } else {
                LogUtil::registerError (_CREATEFAILED);
            }
            return pnRedirect(pnModUrl('Users', 'admin', 'main'));
        }

    } else {
        return LogUtil::registerError(_USERS_NOUSERSELECTED);
    }
}

/**
 * users_admin_modify()
 *
 * @param  $args
 * @return string HTML string
 */
function users_admin_modify($args)
{
    // security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)){
        return LogUtil::registerPermissionError();
    }

    // get our input
    $op     = FormUtil::getPassedValue('op', (isset($args['op']) ? $args['op'] : null), 'GET');
    $userid = FormUtil::getPassedValue('userid', (isset($args['userid']) ? $args['userid'] : null), 'GET');
    $uname  = FormUtil::getPassedValue('uname', (isset($args['uname']) ? $args['uname'] : null), 'GET');
    if (is_null($userid) && !is_null($uname) && !empty($uname)) {
        $userid = pnUserGetIDFromName($uname);
    }

    // get the user vars
    $uservars = pnUserGetVars($userid);
    if ($uservars == false) {
        return LogUtil::registerError(_NOSUCHITEM, 404);
    }

    // load additional language files
    pnModLangLoad('Profile', 'user');
    pnModLangLoad('Theme', 'admin');

    // create the output oject
    $pnRender = pnRender::getInstance('Users', false);

    // urls
    $pnRender->assign('urlprocessusers', pnModUrl('Users', 'admin', 'processusers', array('op' => 'edit', 'do' => 'yes')));
    $pnRender->assign('op', 'edit');


    $pnRender->assign('userid',   $userid);
    $pnRender->assign($uservars);

    $items = pnModAPIFunc('Profile', 'user', 'getallactive');

    // assign the items to the template
    $pnRender->assign('duditems', $items);

    $permissions_array=array();
    $access_types_array=array();
    $usergroups = pnModAPIFunc('Groups', 'user', 'getusergroups', array('uid' => $userid));
    foreach ($usergroups as $usergroup) {
        $permissions_array[] = (int)$usergroup['gid'];
    }
    $allgroups = pnModAPIFunc('Groups', 'user', 'getall');
    foreach ($allgroups as $group) {
        $access_types_array[$group['gid']]=$group['name'];
    }

    $pnRender->assign('permissions_array',  $permissions_array);
    $pnRender->assign('access_types_array', $access_types_array);

    return $pnRender->fetch('users_admin_modify.htm');
}

/**
 * users_admin_deleteusers()
 *
 * @param $args
 * @return string HTML string
 **/
function users_admin_deleteusers()
{
    // check permissions
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_DELETE)){
        return LogUtil::registerPermissionError();
    }

    // get arguments
    $op     = FormUtil::getPassedValue('op', null, 'GET');
    $userid = FormUtil::getPassedValue('userid', null, 'GET');
    $uname  = FormUtil::getPassedValue('uname', (isset($args['uname']) ? $args['uname'] : null), 'GET');
    if (is_null($userid) && !is_null($uname) && !empty($uname)) {
        $userid = pnUserGetIDFromName($uname);
    }

    if ($userid == 1) {
        return LogUtil::registerError(_MODARGSERROR);
    }

    if (is_null($uname)) {
        $uname = pnUserGetVar('uname', $userid);
    }

    // create the output object
    $pnRender = pnRender::getInstance('Users', false);

    $pnRender->assign('userid', $userid);
    $pnRender->assign('uname', $uname);

    // return output
    return $pnRender->fetch('users_admin_deleteusers.htm');
}

/**
 * users_admin_mailusers()
 *
 * @param  $args
 * @return string HTML string
 */
function users_admin_mailusers()
{
    // check permissions
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // get the input arguments
    $op     = FormUtil::getPassedValue('op', null, 'GET');
    $userid = FormUtil::getPassedValue('userid', null, 'GET');  // array [index] => userid

    // create the output oject
    $pnRender = pnRender::getInstance('Users', false);
    $pnRender->assign('userid', $userid);

    // return the output object
    return $pnRender->fetch('users_admin_mailusers.htm');
}

/**
 * users_admin_modifyconfig()
 *
 * User configuration settings
 * @author Xiaoyu Huang
 * @see function settings_admin_main()
 * @return string HTML string
 **/
function users_admin_modifyconfig()
{
    // Security check
    if (!(SecurityUtil::checkPermission('Users::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Users', false);

    // load the profile module language file
    pnModLangLoad('Profile', 'user');

    // assign the module vars
    $config = pnModGetVar('Users');
    $pnRender->assign('config', $config);

    // Return the output that has been generated by this function
    return $pnRender->fetch('users_admin_modifyconfig.htm');
}

/**
 * users_admin_updateconfing()
 *
 * Update user configuration settings
 * @author Xiaoyu Huang
 * @see function settings_admin_main()
 * @return string HTML string
 **/
function users_admin_updateconfig()
{
    // security check
    if (!(SecurityUtil::checkPermission('Users::', '::', ACCESS_ADMIN)))
        return LogUtil::registerPermissionError();

    // get our input
    $config = FormUtil::getPassedValue('config', '', 'POST');

    if (!isset($config['reg_noregreasons'])) {
        $config['reg_noregreasons'] = '';
    }

    pnModSetVar('Users', 'itemsperpage', $config['itemsperpage']);
    pnModSetVar('Users', 'userimg', $config['userimg']);
    pnModSetVar('Users', 'reg_uniemail', $config['reg_uniemail']);
    pnModSetVar('Users', 'reg_optitems', $config['reg_optitems']);
    pnModSetVar('Users', 'reg_allowreg', $config['reg_allowreg']);
    pnModSetVar('Users', 'reg_noregreasons', $config['reg_noregreasons']);
    pnModSetVar('Users', 'moderation', $config['moderation']);
    pnModSetVar('Users', 'reg_verifyemail', $config['reg_verifyemail']);
    pnModSetVar('Users', 'reg_notifyemail', $config['reg_notifyemail']);
    pnModSetVar('Users', 'reg_Illegaldomains', $config['reg_Illegaldomains']);
    pnModSetVar('Users', 'reg_Illegalusername', $config['reg_Illegalusername']);
    pnModSetVar('Users', 'reg_Illegaluseragents', $config['reg_Illegaluseragents']);
    pnModSetVar('Users', 'minage', $config['minage']);
    pnModSetVar('Users', 'minpass', $config['minpass']);
    pnModSetVar('Users', 'anonymous', $config['anonymous']);
    pnModSetVar('Users', 'savelastlogindate', $config['savelastlogindate']);
    pnModSetVar('Users', 'loginviaoption', $config['loginviaoption']);
    pnModSetVar('Users', 'hash_method', $config['hash_method']);
    pnModSetVar('Users', 'login_redirect', $config['login_redirect']);
    pnModSetVar('Users', 'reg_question', $config['reg_question']);
    pnModSetVar('Users', 'reg_answer', $config['reg_answer']);
    pnModSetVar('Users', 'idnnames', $config['idnnames']);

    // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module','updateconfig','Users', array('module' => 'Users'));

    // the module configuration has been updated successfuly
    LogUtil::registerStatus (_CONFIGUPDATED);

    return pnRedirect(pnModURL('Users', 'admin', 'modifyconfig'));
}