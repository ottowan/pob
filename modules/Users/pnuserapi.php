<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuserapi.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
*/

/**
 * get all example items
 *
 * @param    int     $args['starnum']    (optional) first item to return
 * @param    int     $args['numitems']   (optional) number if items to return
 * @return   array   array of items, or false on failure
 */
function users_userapi_getall($args)
{
    // Optional arguments.
    $startnum = (isset($args['startnum']) && is_numeric($args['startnum'])) ? $args['startnum'] : 1;
    $numitems = (isset($args['numitems']) && is_numeric($args['numitems'])) ? $args['numitems'] : -1;

    // Security check
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_OVERVIEW)) {
        return false;
    }

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
                      'level'            => ACCESS_READ);

    // form where clause
    $where = '';
    if (isset($args['letter'])) {
        $where = "WHERE pn_uname LIKE '".DataUtil::formatForStore($args['letter'])."%'";
    }

    $objArray = DBUtil::selectObjectArray('users', $where, 'uname', $startnum-1, $numitems, '', $permFilter);

    // Check for a DB error
    if ($objArray === false) {
        return LogUtil::registerError (_GETFAILED);
    }

    return $objArray;
}

/**
 * get a specific item
 *
 * @param    $args['uid']  id of user item to get
 * @return   array         item array, or false on failure
 */
function users_userapi_get($args)
{
    // Argument check
    if (!isset($args['uid']) || !is_numeric($args['uid'])) {
        if (!isset($args['uname'])) {
            return LogUtil::registerError (_MODARGSERROR);
        }
    }

    $pntable = pnDBGetTables();
    $userscolumn = $pntable['users_column'];

    // calculate the where statement
    if (isset($args['uid'])) {
        $where = "$userscolumn[uid]='" . DataUtil::formatForStore($args['uid']) . "'";
    } else {
        $where = "$userscolumn[uname]='" . DataUtil::formatForStore($args['uname']) . "'";
    }

    $obj = DBUtil::selectObject('users', $where);

    // Security check
    if ($obj && !SecurityUtil::checkPermission('Users::', "$obj[uname]::$obj[uid]", ACCESS_READ)) {
       return false;
    }

    // Return the item array
    return $obj;
}

/**
 * utility function to count the number of items held by this module
 *
 * TODO: shouldn't there be some sort of limit on the select/loop ??
 *
 * @return   integer   number of items held by this module
 */
function users_userapi_countitems($args)
{
    // form where clause
    $where = '';
    if (isset($args['letter'])) {
        $where = "WHERE pn_uname LIKE '".DataUtil::formatForStore($args['letter'])."%'";
    }

    return DBUtil::selectObjectCount('users', $where);
}

/**
 * users_userapi_optionalitems()
 * get opition items
 *
 * @return array of items, or false on failure
 **/
function users_userapi_optionalitems($args)
{
    $items = array();

    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return $items;
    }

    if (!pnModAvailable('Profile') || !pnModDBInfoLoad('Profile')) {
        return false;
    }

    $pntable = pnDBGetTables();
    $propertycolumn = $pntable['user_property_column'];

    $extrawhere = '';
    if (isset($args['proplabel']) && !empty($args['proplabel'])) {
        $extrawhere = "AND $propertycolumn[prop_label] = '".DataUtil::formatForStore($args['proplabel'])."'";
    }

    $where = "WHERE  $propertycolumn[prop_weight]!=0
              AND    $propertycolumn[prop_dtype]!='-1' $extrawhere";
    $orderby = "ORDER BY $propertycolumn[prop_weight]";

    $objArray = DBUtil::selectObjectArray('user_property', $where, $orderby);

    if ($objArray === false) {
        LogUtil::registerError (_GETFAILED);
        return $objArray;
    }

    $ak = array_keys($objArray);
    foreach($ak as $v) {
        $prop_validation = @unserialize($objArray[$v]['prop_validation']);
        $prop_array = array('prop_viewby'      => $prop_validation['viewby'],
                            'prop_displaytype' => $prop_validation['displaytype'],
                            'prop_listoptions' => $prop_validation['listoptions'],
                            'prop_note'        => $prop_validation['note'],
                            'prop_validation'  => $prop_validation['validation']);
        array_push($objArray[$v], $prop_array);
    }

    return $objArray;
}

/**
 * users_userapi_checkuser()
 * Check whether the user is validated
 *
 * @return errorcodes -1=NoPermission 1=EverythingOK 2=NotaValidatedEmailAddr
 *         3=NotAgreeToTerms 4=InValidatedUserName 5=UserNameTooLong
 *         6=UserNameReserved 7=UserNameIncludeSpace 8=UserNameTaken
 *         9=EmailTaken 11=UserAgentBanned 12=DomainBanned
 **/
function users_userapi_checkuser($args)
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return -1;
    }

    if (!pnVarValidate($args['email'], 'email')) {
        return 2;
    }

    if (pnModAvailable('legal')) {
        if ($args['agreetoterms'] == 0) {
            return 3;
        }
    }

    if ((!$args['uname']) || !(!preg_match("/[[:space:]]/", $args['uname'])) || !pnVarValidate($args['uname'], 'uname')) {
        return 4;
    }

    if (strlen($args['uname']) > 25) {
        return 5;
    }

    // admins are allowed to add any usernames, even those defined as being illegal
    if(!SecurityUtil::checkPermission('Users::', '::', ACCESS_ADMIN)) {
        // check for illegal usernames
        $reg_illegalusername = pnModGetVar('Users', 'reg_Illegalusername');
        if (!empty($reg_illegalusername)) {
            $usernames = explode(" ", $reg_illegalusername);
            $count = count($usernames);
            $pregcondition = "/((";
            for ($i = 0;$i < $count;$i++) {
                if ($i != $count-1) {
                    $pregcondition .= $usernames[$i] . ")|(";
                } else {
                    $pregcondition .= $usernames[$i] . "))/iAD";
                }
            }
            if (preg_match($pregcondition, $args['uname'])) {
                return 6;
            }
        }
    }

    if (strrpos($args['uname'], ' ') > 0) {
        return 7;
    }

    // check existing and active user
    $ucount = DBUtil::selectObjectCountByID ('users', $args['uname'], 'uname', 'lower');
    if ($ucount) {
        return 8;
    }

    // check pending user
    $ucount = DBUtil::selectObjectCountByID ('users_temp', $args['uname'], 'uname', 'lower');
    if ($ucount) {
        return 8;
    }

    if (pnModGetVar('Users', 'reg_uniemail')) {
        $ucount = DBUtil::selectObjectCountByID ('users', $args['email'], 'email');
        if ($ucount) {
            return 9;
        }
    }

    if (pnModGetVar('Users', 'moderation')) {
        $ucount = DBUtil::selectObjectCountByID ('users_temp', $args['uname'], 'uname');
        if ($ucount) {
            return 8;
        }

        $ucount = DBUtil::selectObjectCountByID ('users_temp', $args['email'], 'email');
        if (pnModGetVar('Users', 'reg_uniemail')) {
            if ($ucount) {
                return 9;
            }
        }
    }

    $useragent = strtolower(pnServerGetVar('HTTP_USER_AGENT'));
    $illegaluseragents = pnModGetVar('Users', 'reg_Illegaluseragents');
    if (!empty($illegaluseragents)) {
        $disallowed_useragents = str_replace(', ', ',', $illegaluseragents);
        $checkdisallowed_useragents = explode(',', $disallowed_useragents);
        $count = count($checkdisallowed_useragents);
        $pregcondition = "/((";
        for ($i = 0;$i < $count;$i++) {
            if ($i != $count-1) {
                $pregcondition .= $checkdisallowed_useragents[$i] . ")|(";
            } else {
                $pregcondition .= $checkdisallowed_useragents[$i] . "))/iAD";
            }
        }
        if (preg_match($pregcondition, $useragent)) {
            return 11;
        }
    }

    $illegaldomains = pnModGetVar('Users', 'reg_Illegaldomains');
    if (!empty($illegaldomains)) {
        list($foo, $maildomain) = split('@', $args['email']);
        $maildomain = strtolower($maildomain);
        $disallowed_domains = str_replace(', ', ',', $illegaldomains);
        $checkdisallowed_domains = explode(',', $disallowed_domains);
        if (in_array($maildomain, $checkdisallowed_domains)) {
            return 12;
        }
    }

    return 1;
}

function users_userapi_finishnewuser($args)
{
    $action = true;

    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return false;
    }

    // hash methods array
    $hashmethodsarray = pnModAPIFunc('Users', 'user', 'gethashmethods');

    // make password
    $hash_method = pnModGetVar('Users', 'hash_method');
    $hashmethod = $hashmethodsarray[$hash_method];

    if (isset($args['moderated']) && $args['moderated'] == true) {
        $makepass  = $args['pass'];
        $cryptpass = $args['pass'];
        $activated = 1;
    } else {
        if (pnModGetVar('Users', 'reg_verifyemail')==1 && empty($args['isadmin'])) {
            $makepass = _users_userapi_makePass();
            $cryptpass = DataUtil::hash($makepass, $hash_method);
            $activated = 1;
        } elseif (pnModGetVar('Users', 'reg_verifyemail')==2) {
            $makepass = $args['pass'];
            $cryptpass = DataUtil::hash($args['pass'],$hash_method);
            $activated = 0;
        } else {
            $makepass = $args['pass']; // for welcome email. [class007]
            $cryptpass = DataUtil::hash($args['pass'],$hash_method);
            $activated = 1;
        }
    }

    if (!isset($args['user_regdate']))   $args['user_regdate'] = date("Y-m-d H:i:s", time());
    if (!isset($args['user_viewemail'])) $args['user_viewemail'] = '0';
    if (!isset($args['storynum']))       $args['storynum'] = '5';
    if (!isset($args['commentlimit']))   $args['commentlimit'] = '4096';
    if (!isset($args['timezoneoffset'])) $args['timezoneoffset'] = pnConfigGetVar('timezone_offset');

    $sitename  = pnConfigGetVar('sitename');
    $siteurl   = pnGetBaseURL();
    $adminmail = pnConfigGetVar('adminmail');

    if (isset($args['moderated']) && $args['moderated']) {
        $moderation = false;
    } else {
        $moderation = pnModGetVar('Users', 'moderation');
        $args['moderated'] = false;
    }

    pnModDBInfoLoad('Profile');
    $pntable = pnDBGetTables();

    // We keep dynata as is if moderation is on as all dynadata will go in one field
    if ($moderation) {
        $column     = $pntable['users_temp_column'];
        $columnid   = $column['tid'];
    } else {
        $column     = $pntable['users_column'];
        $columnid   = $column['uid'];
    }

    // create output object
    $pnRender = pnRender::getInstance('Users', false);
    $pnRender->assign('sitename', $sitename);
    $pnRender->assign('siteurl', substr($siteurl, 0, strlen($siteurl)-1));

    $obj = array();
    // do moderation stuff and exit
    if ($moderation) {
        $obj['uname']    = $args['uname'];
        $obj['email']    = $args['email'];
        $obj['femail']   = $args['femail'];
        $obj['pass']     = $cryptpass;
        $obj['dynamics'] = serialize($args['dynadata']);
        $obj['comment']  = $args['comment'];
        $obj['type']     = 1;
        $obj['tag']      = 0;
        $res = DBUtil::insertObject ($obj, 'users_temp', 'tid');

        if (!$res) {
            return false;
        }

        $pnRender->assign('email', $args['email']);
        $pnRender->assign('uname', $args['uname']);
        //$pnRender->assign('uid', $args['uid']);
        $pnRender->assign('makepass', $makepass);
        $pnRender->assign('moderation', $moderation);
        $pnRender->assign('moderated', $args['moderated']);

        // Password Email - Must be send now as the password will be encrypted and unretrievable later on.
        $message = $pnRender->fetch('users_userapi_welcomeemail.htm');
        $subject = pnML('_USERS_WELCOMESUBJECT', array('sitename' => $sitename, 'uname' => $args['uname']));
        pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $args['email'], 'subject' => $subject, 'body' => $message, 'html' => true));

        if (pnModGetVar('Users', 'reg_notifyemail') != '' && $moderation == 1) {
            $email2 = pnModGetVar('Users', 'reg_notifyemail');
            $subject2 = _USERS_NOTIFYEMAILSUBJECT;
            $message2 = $pnRender->fetch('users_userapi_adminnotificationmail.htm');
            pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $email2, 'subject' => $subject2, 'body' => $message2, 'html' => true));
        }

        return $obj['tid'];
    }

    // no moderation - do other actions
    // set some default values - Until we're sure what we'll do...
    $dynadata = $args['dynadata'];
    $name         = (empty($dynadata['_UREALNAME']))    ? '' : $dynadata['_UREALNAME'];
    $location     = (empty($dynadata['_YLOCATION']))    ? '' : $dynadata['_YLOCATION'];
    $femail       = (empty($dynadata['_UFAKEMAIL']))    ? '' : $dynadata['_UFAKEMAIL'];
    $url          = (empty($dynadata['_YOURHOMEPAGE'])) ? '' : $dynadata['_YOURHOMEPAGE'];
    $user_avatar  = (empty($dynadata['_YOURAVATAR']))   ? '' : $dynadata['_YOURAVATAR'];
    $user_icq     = (empty($dynadata['_YICQ']))         ? '' : $dynadata['_YICQ'];
    $user_occ     = (empty($dynadata['_YOCCUPATION']))  ? '' : $dynadata['_YOCCUPATION'];
    $user_from    = (empty($dynadata['_YLOCATION']))    ? '' : $dynadata['_YLOCATION'];
    $user_intrest = (empty($dynadata['_YINTERESTS']))   ? '' : $dynadata['_YINTERESTS'];
    $user_sig     = (empty($dynadata['_SIGNATURE']))    ? '' : $dynadata['_SIGNATURE'];
    $user_aim     = (empty($dynadata['_YAIM']))         ? '' : $dynadata['_YAIM'];
    $user_yim     = (empty($dynadata['_YYIM']))         ? '' : $dynadata['_YYIM'];
    $user_msnm    = (empty($dynadata['_YMSNM']))        ? '' : $dynadata['_YMSNM'];
    $bio          = (empty($dynadata['_EXTRAINFO']))    ? '' : $dynadata['_EXTRAINFO'];

    $obj['uname']           = $args['uname'];
    $obj['email']           = $args['email'];
    $obj['femail']          = isset($args['femail']) ? $args['femail'] : null;
    $obj['user_regdate']    = $args['user_regdate'];
    $obj['user_viewemail']  = $args['user_viewemail'];
    $obj['user_theme']      = '';
    $obj['pass']            = $cryptpass;
    $obj['storynum']        = $args['storynum'];
    $obj['ublockon']        = 0;
    $obj['ublock']          = '';
    $obj['theme']           = '';
    $obj['counter']         = 0;
    $obj['timezone_offset'] = $args['timezoneoffset'];
    $obj['activated']       = $activated;
    $obj['hash_method']     = $hashmethod;
    $res = DBUtil::insertObject ($obj, 'users', 'uid');

    if (!$res) {
        return false;
    }

    $uid = $obj['uid'];

    $proptbl = $pntable['user_property'];
    $dynatbl = $pntable['user_data'];
    $propcol = $pntable['user_property_column'];
    $dynacol = $pntable['user_data_column'];

    $where = "WHERE $propcol[prop_dtype]>'0'";
    $dynsql = DBUtil::selectObjectArray ('user_property', $where);

    if ($dynsql <> false) {

        foreach ($dynsql as $dyn) {
            $propid    = $dyn['prop_id'];
            $proplabel = $dyn['prop_label'];

            if (isset($dynadata[$proplabel])) {
                $obj = array();
                $obj['uda_propid'] = $propid;
                $obj['uda_uid']    = $uid;
                $obj['uda_value']  = $dynadata[$proplabel];
                $res = DBUtil::insertObject ($obj, 'user_data');
                if (!$res) {
                    return false;
                }
            }
        }
    }

    // Add user to group
    // TO DO - move this to a groups API calls
    $gname = pnModGetVar('Groups', 'defaultgroup');
    $group = DBUtil::selectObjectByID ('groups', $gname, 'name');
    if (!$group) {
        return false;
    }

    $obj = array();
    $obj['gid'] = $group['gid'];
    $obj['uid'] = $uid;
    $res = DBUtil::insertObject ($obj, 'group_membership', 'dummy');
    if (!$res) {
        return false;
    }

    $from = pnConfigGetVar('adminmail');

    // begin mail user
    $pnRender->assign('email', $args['email']);
    $pnRender->assign('uname', $args['uname']);
    $pnRender->assign('uid', $uid);
    $pnRender->assign('makepass', $makepass);
    $pnRender->assign('moderated', $args['moderated']);
    $pnRender->assign('moderation', $moderation);
    $pnRender->assign('user_regdate', $args['user_regdate']);

    if ($activated == 1) {
        // Password Email & Welcome Email
        $message = $pnRender->fetch('users_userapi_welcomeemail.htm');
        $subject = pnML('_USERS_WELCOMESUBJECT', array('sitename' => $sitename, 'uname' => $args['uname']));
        pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $args['email'], 'subject' => $subject, 'body' => $message, 'html' => true));
    } else {
        // Activation Email
        $subject =  pnML('_USERS_ACTIVATIONSUBJECT', array('uname' => $args['uname']));
        // add en encoded activation code. The string is split with a hash (this character isn't used by base 64 encoding)
        $pnRender->assign('code', base64_encode($uid . '#' . $args['user_regdate']));
        $message = $pnRender->fetch('users_userapi_activationemail.htm');
        pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $args['email'], 'subject' => $subject, 'body' => $message, 'html' => true));
    }

    //mail notify email
    if (pnModGetVar('Users', 'reg_notifyemail') != '') {
        $email2 = pnModGetVar('Users', 'reg_notifyemail');
        $subject2 = _USERS_NOTIFYEMAILSUBJECT;
        $message2 = $pnRender->fetch('users_userapi_adminnotificationemail.htm');
        pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $email2, 'subject' => $subject2, 'body' => $message2, 'html' => true));
    }

    // Let other modules know we have created an item
    pnModCallHooks('item', 'create', $uid, array('module' => 'Users'));

    return $uid;
}

/**
 * users_userapi_mailpasswd()
 *
 * @param $args
 * @return code 0=DatabaseError 2=NoSuchUsername 3=PasswordMailed 4=ConfirmationCodeMailed
 **/
function users_userapi_mailpasswd($args)
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return false;
    }

    $pntable = pnDBGetTables();

    $sitename = pnConfigGetVar('sitename');
    $system = pnConfigGetVar('system');
    $adminmail = pnConfigGetVar('adminmail');

    $column = $pntable['users_column'];
    $wheres = array();
    if (!empty($args['email'])) {
        $wheres[] = "$column[email] = '" . DataUtil::formatForStore($args['email']) . "'";
        $who = $args['email'];
    }
    if (!empty($args['uname'])) {
        $wheres[] = "$column[uname] = '" . DataUtil::formatForStore($args['uname']) . "'";
        $who = $args['uname'];
    }
    $where = join('AND ', $wheres);
    $user  = DBUtil::selectObject('users', $where);
    if (!$user) {
        return 2;
    }

    $pnRender = pnRender::getInstance('Users', false);
    $pnRender->assign('uname', $user['uname']);
    $pnRender->assign('sitename', $sitename);
    $pnRender->assign('hostname', pnServerGetVar('REMOTE_ADDR'));

    $areyou = substr($user['pass'], 0, 5);

    if ($areyou == $args['code']) {
        $pnRender->assign('password', $newpass=_users_userapi_makePass());
        $pnRender->assign('url', pnGetBaseURL().pnModURL('Users', 'user', 'loginscreen'));
        $message = $pnRender->fetch('users_userapi_passwordmail.htm');
        $subject = pnML('_USERS_PASSWORDFOR', array('uname' => $user['uname']));
        pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $user['email'], 
                                                            'subject'   => $subject, 
                                                            'body'      => $message, 
                                                            'html'      => true));
        // Next step: add the new password to the database
        $hash_method = pnModGetVar('Users', 'hash_method');
        $hashmethodsarray = pnModAPIFunc('Users', 'user', 'gethashmethods');
        $cryptpass = DataUtil::hash($newpass, $hash_method);
        $obj = array();
        $obj['uname'] = $user['uname'];
        $obj['pass']  = $cryptpass;
        $obj['hash_method'] = $hashmethodsarray[$hash_method];
        $res = DBUtil::updateObject ($obj, 'users', '', 'uname');
        return ($res ? 3 : 0);
    } else {
        $pnRender->assign('code', substr($user['pass'], 0, 5));
        $pnRender->assign('url', pnGetBaseURL().pnModURL('Users', 'user', 'lostpassword'));
        $message = $pnRender->fetch('users_userapi_lostpasscodemail.htm');
        $subject = pnML('_USERS_CODEFOR', array('uname' => $user['uname']));
        pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $user['email'], 
                                                            'subject'   => $subject, 
                                                            'body'      => $message, 
                                                            'html'      => true));
        return 4;
    }
}

/**
 * users_userapi_activateuser()
 *
 *
 * @param $args
 * @return bool
 **/
function users_userapi_activateuser($args)
{
    if (!SecurityUtil::checkPermission('Users::', '::', ACCESS_READ)) {
        return false;
    }

    // Preventing reactivation from same link !
    $newregdate = date("Y-m-d H:i:s", strtotime($args['regdate'])+1);
    $obj = array('uid'          => $args['uid'],
                 'activated'    => '1',
                 'user_regdate' => DataUtil::formatForStore($newregdate));

    return (boolean)DBUtil::updateObject($obj, 'users', '', 'uid');
}


function users_userapi_expiredsession($args)
{
    $pnRender = pnRender::getInstance('Users', false);
    return $pnRender->fetch('users_userapi_expiredsession.htm');
}


function _users_userapi_makePass()
{
    // todo - add password length configurability to user admin
    return RandomUtil::getString(8, 8, false, false, true, false, true, false, true, array('0', 'o', 'l', '1'));
}


function users_userapi_gethashmethods($args)
{
    $reverse = isset($args['reverse']) ? $args['reverse'] : false;
    if ($reverse)
    {
        return array(1 => 'md5',
                     5 => 'sha1',
                     8 => 'sha256');
    }
    else
    {
        return array('md5'    => 1,
                     'sha1'   => 5,
                     'sha256' => 8);
    }
}
