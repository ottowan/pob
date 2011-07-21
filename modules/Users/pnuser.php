<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuser.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
*/

/**
 * main function
 * if user isn't login, direct he to getlogin screen
 * else to your account screen
 */
function users_user_main()
{
    if (!pnUserLoggedIn()) {
        return pnModFunc('Users', 'user',  'view');
    } else {
        if (pnModAvailable('Profile')) {
            return pnModFunc('Profile', 'user', 'main');
        } else {
            //LogUtil::registerError(_USERS_PPROFILEMODULENOTAVAILABLE);
            return pnRedirect(pnConfigGetVar('entrypoint', 'index.php'));
        }
    }
}

/**
 * display the base user form (login/lostpassword/register options)
 */
function users_user_view($args)
{
    // If has logged in, header to index.php
    if (pnUserLoggedIn()) {
        return pnRedirect(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // create output object
    $pnRender = pnRender::getInstance('Users');

    // other vars
    $pnRender->assign(pnModGetVar('Users'));

    return $pnRender->fetch('users_user_view.htm');
}

/**
 * display the login form
 *
 * @param bool stop display the invalid username/password message
 * @param int redirectype type of redirect 0 = redirect to referer (default), 1 = redirect to current uri
 */
function users_user_loginscreen($args)
{
    // create output object
    $pnRender = pnRender::getInstance('Users');

    // we shouldn't get here if logged in already....
    if (pnUserLoggedIn()) {
        return pnRedirect(pnModURL('Users', 'user', 'main'));
    }

    $redirecttype = (int)FormUtil::getPassedValue('redirecttype', isset($args['redirecttype']) ? $args['redirecttype'] : 0, 'GET');
    if ($redirecttype == 0) {
        $returnurl = pnServerGetVar('HTTP_REFERER');
    } else {
        $returnurl = pnGetCurrentURI();
    }
    if (empty($returnurl)) {
        $returnurl = pnGetBaseURL();
    }

    // assign variables for the template
    $pnRender->assign('seclevel', pnConfigGetVar('seclevel'));
    $pnRender->assign('returnurl', $returnurl);
    // do we have to show a note about reconfirming the terms of use?
    $pnRender->assign('confirmtou', SessionUtil::getVar('confirmtou', 2));

    return $pnRender->fetch('users_user_loginscreen.htm');
}

/**
 * set an underage flag and route the user back to the first user page
 */
function users_user_underage($args)
{
    LogUtil::registerError (pnML('_USERS_AGEREQUIREMENTNOTMET', array('a' => pnModGetVar('Users', 'minage'))));
    return pnRedirect(pnModURL('Users', 'user', 'view'));
}

/**
 * display the registration form
 */
function users_user_register($args)
{
    // If has logged in, header to index.php
    if (pnUserLoggedIn()) {
        return pnRedirect(pnConfigGetVar('entrypoint', 'index.php'));
    }

    $template = 'users_user_register.htm';
    // check if we've agreed to the age limit
    if (pnModGetVar('Users', 'minage') != 0 && !stristr(pnServerGetVar('HTTP_REFERER'), 'register')) {
        $template = 'users_user_checkage.htm';
    }

    // Load Profile language
    pnModLangLoad('Profile', 'user');

    // create output object
    $pnRender = pnRender::getInstance('Users', false);

    // other vars
    $modvars = pnModGetVar('Users');

    $pnRender->assign($modvars);
    $pnRender->assign('sitename', pnConfigGetVar('sitename'));
    $pnRender->assign('legal',    pnModAvailable('legal'));
    $pnRender->assign('optitems', pnModAPIFunc('Profile', 'user', 'getallactive', array()));

    return $pnRender->fetch($template);
}

/**
 * display the lost password form
 */
function users_user_lostpassword($args)
{
    // we shouldn't get here if logged in already....
    if (pnUserLoggedIn()) {
        return pnRedirect(pnModURL('Users', 'user', 'main'));
    }

    // create output object
    $pnRender = pnRender::getInstance('Users');

    return $pnRender->fetch('users_user_lostpassword.htm');
}

/**
 * login function
 * login a user. if username or password is wrong, display error msg.
 */
function users_user_login()
{
    // we shouldn't get here if logged in already....
    if (pnUserLoggedIn()) {
        return pnRedirect(pnModURL('Users', 'user', 'main'));
    }

    if (!SecurityUtil::confirmAuthKey('Users')) {
        return LogUtil::registerAuthidError (pnModURL('Users','user','loginscreen'));
    }

    $uname      = FormUtil::getPassedValue ('uname');
    $email      = FormUtil::getPassedValue ('email');
    $pass       = FormUtil::getPassedValue ('pass');
    $url        = FormUtil::getPassedValue ('url');
    $rememberme = FormUtil::getPassedValue ('rememberme', '');

    $confirmtou = SessionUtil::getVar('confirmtou', 2);
    if ($confirmtou==0) {
        $touaccepted = (int)FormUtil::getPassedValue('touaccepted', 0, 'GETPOST');
        if ($touaccepted<>1) {
            // user ad to accept the terms of use, but didn't
            return pnRedirect(pnModURL('Users','user','loginscreen'));
        }
        SessionUtil::setVar('confirmtou', 1);
        $confirmtou = 1;
    }

    $loginoption    = pnModGetVar('Users', 'loginviaoption');
    $login_redirect = pnModGetVar('Users', 'login_redirect');

    if (pnUserLogIn((($loginoption==1) ? $email : $uname), $pass, $rememberme)) {
        if ($login_redirect == 1) {
            // WCAG compliant login
            return pnRedirect($url);
        } else {
            // meta refresh
            users_print_redirectpage(_USERS_LOGGINGYOUIN, $url);
        }
        return true;
    } else {
        // check if we stopped because of terms of use reconfirmation
        $confirmtou = SessionUtil::getVar('confirmtou', 2);
        if ($confirmtou <> 2) {
            LogUtil::registerStatus(_USERS_LOGININCOMPLETE);
        } else {
            LogUtil::registerError (_USERS_LOGININCORRECT);
            $reg_verifyemail = pnModGetVar('Users', 'reg_verifyemail');
            if ($reg_verifyemail == 2) {
                LogUtil::registerError(_USERS_ACCOUNTPOSSIBLYINACTIVE);
            }
        }
        return pnRedirect(pnModURL('Users','user','loginscreen'));
    }
}

/**
 * login function
 * login a user. if username or password is wrong, display error msg.
 */
function users_user_logout()
{
    $login_redirect = pnModGetVar('Users', 'login_redirect');

    if (pnUserLogOut()) {
        if ($login_redirect == 1) {
            // WCAG compliant logout - we redirect to index.php because
            // we might no have the permission for the recent site any longer
            return pnRedirect(pnConfigGetVar('entrypoint', 'index.php'));
        } else {
            // meta refresh
            users_print_redirectpage(_USERS_YOUARELOGGEDOUT);
        }
    } else {
        LogUtil::registerError (_USERS_YOUARENOTLOGGEDOUT);
        return pnRedirect(pnConfigGetVar('entrypoint', 'index.php'));
    }
    return true;
}

/**
 * users_user_finishnewuser()
 *
 */
function users_user_finishnewuser()
{
    if (!SecurityUtil::confirmAuthKey('Users')) {
        return LogUtil::registerAuthidError (pnModURL('Users','user','register'));
    }

    $uname          = strtolower(FormUtil::getPassedValue ('uname', null, 'POST'));
    $agreetoterms   = FormUtil::getPassedValue ('agreetoterms', null, 'POST');
    $email          = FormUtil::getPassedValue ('email', null, 'POST');
    $vemail         = FormUtil::getPassedValue ('vemail', null, 'POST');
    $pass           = FormUtil::getPassedValue ('pass', null, 'POST');
    $vpass          = FormUtil::getPassedValue ('vpass', null, 'POST');
    $user_viewemail = FormUtil::getPassedValue ('user_viewmail', null, 'POST');
    $reg_answer     = FormUtil::getPassedValue('reg_answer', null, 'POST');

    $optionals = false;
    $dynadata = array();

    // some defaults for error detection and redirection
    $msgtype = 'error';
    $redirectfunc = 'loginscreen';

    // Verify dynamic user data
    if (pnModGetVar('Users', 'reg_optitems') == 1) {
        // Load Profile language
        pnModLangLoad('Profile', 'user');

        $dynadata  = FormUtil::getPassedValue ('dynadata');
        $optionals = true;

        $checkrequired = pnModAPIFunc('Profile', 'user', 'checkrequired',
                                      array('dynadata' => $dynadata));
        if ($checkrequired != false)
        {
            $message = _USERS_ERRORINREQUIREDFIELDS . ' ('.$checkrequired['translatedFieldsStr'].')';
        }
    }

    // because index.php use $name var $name can not get correct value. [class007]
    $name         = $uname;
    $commentlimit = (int)pnModGetVar('Users', 'commentlimit', 0);
    $storynum     = (int)pnModGetVar('Users', 'storyhome', 10);
    $minpass      = (int)pnModGetVar('Users', 'minpass', 5);
    $user_regdate = date("Y-m-d H:i:s", time());

    // Todo (To be removed) :
    // Add require check for dynamics.
    $checkuser = pnModAPIFunc('Users', 'user', 'checkuser',
                              array('uname'        => $uname,
                                    'email'        => $email,
                                    'agreetoterms' => $agreetoterms));

    // if errorcode != 1 then return error msgs
    // Todo : Add require check for dynamics
    if ($checkuser != 1) {
        switch($checkuser){
            case -1:
                $message = _MODULENOAUTH;
                break;
            case 2:
                $message =  _USERS_INVALIDEMAIL;
                break;
            case 3:
                $message =  _USERS_ERRORMUSTAGREE;
                break;
            case 4:
                $message =  _USERS_USERNAMEINVALID;
                break;
            case 5:
                $message =  _USERS_USERNAMETOOLONG;
                break;
            case 6:
                $message =  _USERS_USERNAMERESERVED;
                break;
            case 7:
                $message =  _USERS_USERNAMENOSPACES;
                break;
            case 8:
                $message =  _USERS_USERNAMETAKEN;
                break;
            case 9:
                $message =  _USERS_EMAILREGISTERED;
                break;
            case 11:
                $message =  _USERS_USERAGENTBANNED;
                break;
            case 12:
                $message =  _USERS_EMAILDOMAINBANNED;
                break;
            default:
                $message =  _MODULENOAUTH;
        } // switch
        LogUtil::registerError($message);
        return pnRedirect(pnModURL('Users','user','register'));
    }

    if ($email !== $vemail) {
        $message = _USERS_EMAILSDIFF;
    }

    if (!$modvars['reg_verifyemail'] || $modvars['reg_verifyemail'] == 2) {
        if ((isset($pass)) && ("$pass" != "$vpass")) {
            $message = _USERS_PASSDIFFERENT;
        } elseif (isset($pass) && (strlen($pass) < $minpass)) {
            $message =  pnML('_USERS_YOURPASSMUSTBETHISLONG', array('x' => $minpass));
        } elseif (empty($pass) && !pnModGetVar('Users', 'reg_verifyemail')) {
            $message =  _USERS_PASSWORDREQUIRED;
        }
    }

    if (pnModGetVar('Users', 'reg_question') != '' && pnModGetVar('Users', 'reg_answer') != '') {
        if ($reg_answer != pnModGetVar('Users', 'reg_answer')) {
            $message = _USERS_REGANSWERINCORRECT;
        }
    }

    if (isset($message)) {
        LogUtil::registerError ($message);
        return pnRedirect(pnModURL('Users','user','register'));
    }

    if (empty($dynadata['YOURAVATAR'])) $dynadata['YOURAVATAR'] = 'blank.gif';
    $dynadata['_UFAKEMAIL'] = (!empty($user_viewemail)) ? $email : '';

    // Todo (Will be removed) :
    // Clean up

    $registered = pnModAPIFunc('Users', 'user', 'finishnewuser',
                               array('uname'         => $uname,
                                     'pass'          => $pass,
                                     'email'         => $email,
                                     'user_regdate'  => $user_regdate,
                                     'storynum'      => $storynum,
                                     'commentlimit'  => $commentlimit,
                                     'dynadata'      => $dynadata,
                                     'optionals'     => $optionals));

    if (!$registered) {
        LogUtil::registerError(_USERS_REGISTRATIONFAILED);
    } else {
        if ((int)pnModGetVar('Users', 'moderation')==1) {
            LogUtil::registerStatus(_USERS_APPLICATIONRECEIVED);
            $pnr = pnRender::getInstance('Users');
            return $pnr->fetch('users_user_registrationfinished.htm');
        } else {
            LogUtil::registerStatus(_USERS_YOUAREREGISTERED);
            if (pnModGetVar('Users', 'reg_verifyemail') == 2) {
                LogUtil::registerStatus(_USERS_ACTIVATIONINFO);
            }
            return pnRedirect(pnModURL('Users', 'user', $redirectfunc));
        }
    }

    return pnRedirect(pnGetBaseURL());
}

/**
 * users_user_mailpasswd()
 *
 */
function users_user_mailpasswd()
{
    $uname = FormUtil::getPassedValue ('uname', null, 'POST');
    $email = FormUtil::getPassedValue ('email', null, 'POST');
    $code  = FormUtil::getPassedValue ('code',  null, 'POST');

    $returncode = pnModAPIFunc('Users', 'user', 'mailpasswd',
                               array('uname' => $uname,
                                     'email' => $email,
                                     'code'  => $code));

    if (!empty($email)) {
        $who = $email;
    }
    if (!empty($uname)) {
        $who = $uname;
    }

    if (!$email && !$uname) {
        LogUtil::registerError (_USERS_MISSINGUSERNAME);
        return pnRedirect(pnModURL('Users', 'user', 'lostpassword'));
    }

    SessionUtil::delVar('lostpassword_uname');
    SessionUtil::delVar('lostpassword_email');

    switch($returncode) {
        case -1:
            $message = _UPDATEFAILED;
            break;
        case 2:
            $message = _USERS_NOUSERINFOFOUND;
            break;
        case 3:
            $message = pnML('_USERS_PASSWORDMAILED', array('uname' => $who));
            break;
        case 4:
            $message = pnML('_USERS_CODEMAILED', array('uname' => $who));
            // save username and password for redisplay
            SessionUtil::setVar('lostpassword_uname', $uname);
            SessionUtil::setVar('lostpassword_email', $email);
            break;
        default:
            return false;
    } // switch

    if ($returncode < 3) {
        LogUtil::registerError($message);
    }
    else {
        LogUtil::registerStatus($message);
    }

    switch($returncode){
        case 3:
            return pnRedirect(pnModURL('Users', 'user', 'loginscreen'));
            break;
        default:
            return pnRedirect(pnModURL('Users', 'user', 'lostpassword'));
    }
}

/**
 * users_user_activation($args)
 *
 * Get rid of user activation Link
 *
 */
function users_user_activation($args)
{
    $code = base64_decode(FormUtil::getPassedValue('code', (isset($args['code']) ? $args['code'] : null), 'GETPOST'));
    $code = explode('#', $code);

    if (!isset($code[0]) || !isset($code[1])) {
        return LogUtil::registerError(_USERS_USERACTIVATIONFAILED);
    }
    $uid = $code[0];
    $code = $code[1];

    // Get user Regdate
    $regdate = pnUserGetVar('user_regdate', $uid);

    // Checking length in case the date has been stripped from its space in the mail.
    if (strlen($code) == 18) {
        if (!strpos($code, ' ')) {
            $code = substr($code, 0, 10) . ' ' . substr($code, -8);
        }
    }

    if (DataUtil::hash($regdate, 'md5') == DataUtil::hash($code, 'md5')) {
        $returncode = pnModAPIFunc('Users', 'user', 'activateuser',
                                   array('uid'     => $uid,
                                         'regdate' => $regdate));

        if (!$returncode) {
            return LogUtil::registerError(_USERS_USERACTIVATIONFAILED);
        }
        LogUtil::registerStatus (_USERS_USERACTIVATED);
        return pnRedirect(pnModURL('Users', 'user', 'loginscreen'));
    } else {
        return LogUtil::registerError(_USERS_INVALIDREGCODE);
    }
}

/**
 * print a redirect page
 * original function name is 'redirect_index' in NS-User/tools.php
 *
 * @access private
 */
function users_print_redirectpage($message, $url)
{
    $pnRender = pnRender::getInstance('Users');
    $url = (!isset($url) || empty($url)) ? pnConfigGetVar('entrypoint', 'index.php') : $url;
    
    // check the url
    if (substr($url, 0, 1) == '/') {
        // Root-relative links
        $url = 'http'.(pnServerGetVar('HTTPS')=='on' ? 's' : '').'://'.pnServerGetVar('HTTP_HOST').$url;
    } elseif (!preg_match('!^(?:http|https):\/\/!', $url)) {
        // Removing leading slashes from redirect url
        $url = preg_replace('!^/*!', '', $url);
        // Get base URL and append it to our redirect url
        $baseurl = pnGetBaseURL();
        $url = $baseurl.$url;
    }

    $pnRender->assign('ThemeSel', pnConfigGetVar('Default_Theme'));
    $pnRender->assign('url', $url);
    $pnRender->assign('message', $message);
    $pnRender->assign('redirectmessage', _USERS_LOGGINGREDIRECT);
    $pnRender->display('users_user_redirectpage.htm');
    return true;
}

/**
 * login to disabled site
 *
 */
function users_user_siteofflogin()
{
    // do not process if the site is enabled
    if (!pnConfigGetVar('siteoff', false)) {
        $path = dirname(pnServerGetVar('PHP_SELF'));
        $path = str_replace('\\', '/', $path);
        return pnRedirect($path . '/' . pnConfigGetVar('entrypoint', 'index.php'));
    }

    $user = FormUtil::getPassedValue('user', null, 'POST');
    $pass = FormUtil::getPassedValue('pass', null, 'POST');

    pnUserLogIn($user, $pass, false);

    if (!SecurityUtil::checkPermission('Settings::', 'SiteOff::', ACCESS_ADMIN)) {
        pnUserLogOut();
    }

    $path = dirname(pnServerGetVar('PHP_SELF'));
    $path = str_replace('\\', '/', $path);
    return pnRedirect($path . '/' . pnConfigGetVar('entrypoint', 'index.php'));
}

/**
 * display the configuration options for the users block
 *
 */
function users_user_usersblock()
{
    $blocks = pnModAPIFunc('Blocks', 'user', 'getall');
    $mid = pnModGetIDFromName('Users');
    $found = false;
    foreach ($blocks as $block) {
        if ($block['mid'] == $mid && $block['bkey'] == 'user') {
            $found = true;
            break;
        }
    }

    if (!$found) {
        return _MODULENOAUTH;
    }

    $pnRender = pnRender::getInstance('Users');
    $pnRender->assign(pnUserGetVars(pnUserGetVar('uid')));
    return $pnRender->fetch('users_user_usersblock.htm');
}

/**
 * update users block
 *
 */
function users_user_updateusersblock()
{
    if (!pnUserLoggedIn()) {
        return _MODULENOAUTH;
    }

    $blocks = pnModAPIFunc('Blocks', 'user', 'getall');
    $mid = pnModGetIDFromName('Users');
    $found = false;
    foreach ($blocks as $block) {
        if ($block['mid'] == $mid && $block['bkey'] == 'user') {
            $found = true;
            break;
        }
    }

    if (!$found) {
        return _MODULENOAUTH;
    }

    $uid = pnUserGetVar('uid');
    $ublockon = (bool)FormUtil::getPassedValue('ublockon', false, 'POST');
    $ublock = (string)FormUtil::getPassedValue('ublock', '', 'POST');

    pnUserSetVar('ublockon', $ublockon);
    pnUserSetVar('ublock', $ublock);

    LogUtil::registerStatus (_USERS_USERBLOCKUPDATED);
    return pnRedirect(pnModURL('Users'));
}
