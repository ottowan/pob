<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnajax.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Frank Schummertz, Frank Chestnut
 * @package Zikula_System_Modules
 * @subpackage Users
 */

/**
 * getusers
 * performs a user search based on the fragment entered so far
 *
 * @author Frank Schummertz
 * @param fragment string the fragment of the username entered
 * @return void nothing, direct ouptut using echo!
 */
function Users_ajax_getusers()
{
    $fragment = FormUtil::getpassedValue('fragment');

    pnModDBInfoLoad('Users');
    $pntable = pnDBGetTables();

    $userscolumn = $pntable['users_column'];

    $where = 'WHERE ' . $userscolumn['uname'] . ' REGEXP \'(' . DataUtil::formatForStore($fragment) . ')\'';
    $results = DBUtil::selectObjectArray('users', $where);

    $out = '<ul>';
    if (is_array($results) && count($results) > 0) {
        foreach($results as $result) {
            $out .= '<li>' . DataUtil::formatForDisplay($result['uname']) .'<input type="hidden" id="' . DataUtil::formatForDisplay($result['uname']) . '" value="' . $result['uid'] . '" /></li>';
        }
    }
    $out .= '</ul>';
    echo DataUtil::convertToUTF8($out);
    return true;
}

/**
 * Check new user information
 *
 * Check whether the user can be validated
 *
 * @author Frank Chestnut
 * @param username
 * @param email
 * @param dynadata - Coming soon
 * @return mixed true or Ajax error
 * errorcodes -1=NoPermission 1=EverythingOK 2=NotaValidatedEmailAddr
 *            3=NotAgreeToTerms 4=InValidatedUserName 5=UserNameTooLong
 *            6=UserNameReserved 7=UserNameIncludeSpace 8=UserNameTaken
 *            9=EmailTaken 10=emails different 11=User Agent Banned
 *            12=Email Domain banned 13=DUD incorrect 14=spam question incorrect 
 *            15=Pass too short 16=Pass different 17=No pass 
 **/
function Users_ajax_checkuser()
{
    if (!SecurityUtil::confirmAuthKey()) {
        AjaxUtil::error(FormUtil::getPassedValue('authid') . ' : ' . _BADAUTHKEY);
    }

    $modvars = pnModGetVar('Users');

    pnModLangLoad('Users', 'user');

    if (!$modvars['reg_allowreg']) {
        AjaxUtil::error(_USERS_NOTALLOWREG);
    }

    $uname        = DataUtil::convertFromUTF8(FormUtil::getPassedValue('uname',  null,     'POST'));
    $email        = DataUtil::convertFromUTF8(FormUtil::getPassedValue('email',  null,     'POST'));
    $vemail       = DataUtil::convertFromUTF8(FormUtil::getPassedValue('vemail', null,     'POST'));
    $agreetoterms = DataUtil::convertFromUTF8(FormUtil::getPassedValue('agreetoterms', 0,  'POST'));
    $dynadata     = DataUtil::convertFromUTF8(FormUtil::getPassedValue('dynadata', null,   'POST'));
    $pass         = DataUtil::convertFromUTF8(FormUtil::getPassedValue('pass', null,       'POST'));
    $vpass        = DataUtil::convertFromUTF8(FormUtil::getPassedValue('vpass', null,      'POST'));
    $reg_answer   = DataUtil::convertFromUTF8(FormUtil::getPassedValue('reg_answer', null, 'POST'));
 
    if ((!$uname) || !(!preg_match("/[[:space:]]/", $uname)) || !pnVarValidate($uname, 'uname')) {
        return array('result' => _USERS_USERNAMEINVALID, 'errorcode' => 4);
    }

    if (strlen($uname) > 25) {
        return array('result' => _USERS_USERNAMETOOLONG, 'errorcode' => 5);
    }

    $reg_illegalusername = $modvars['reg_Illegalusername'];
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
        if (preg_match($pregcondition, $uname)) {
            return array('result' => _USERS_USERNAMERESERVED, 'errorcode' => 6);
        }
    }

    if (strrpos($uname, ' ') > 0) {
        return array('result' => _USERS_USERNAMENOSPACES, 'errorcode' => 7);
    }

    // check existing user
    $ucount = DBUtil::selectObjectCountByID ('users', $uname, 'uname');
    if ($ucount) {
        return array('result' => _USERS_USERNAMETAKEN, 'errorcode' => 8);
    }

    // check pending user
    $ucount = DBUtil::selectObjectCountByID ('users_temp', $uname, 'uname');
    if ($ucount) {
        return array('result' => _USERS_USERNAMETAKEN, 'errorcode' => 8);
    }

    if (!pnVarValidate($email, 'email')) {
        return array('result' => _USERS_INVALIDEMAIL, 'errorcode' => 2);
    }

    if ($modvars['reg_uniemail']) {
        $ucount = DBUtil::selectObjectCountByID ('users', $email, 'email');
        if ($ucount) {
            return array('result' => _USERS_EMAILREGISTERED, 'errorcode' => 9);
        }
    }

    if ($modvars['moderation']) {
        $ucount = DBUtil::selectObjectCountByID ('users_temp', $uname, 'uname');
        if ($ucount) {
            return array('result' => _USERS_USERNAMETAKEN, 'errorcode' => 8);
        }

        $ucount = DBUtil::selectObjectCountByID ('users_temp', $email, 'email');
        if (pnModGetVar('Users', 'reg_uniemail')) {
            if ($ucount) {
                return array('result' => _USERS_EMAILREGISTERED, 'errorcode' => 9);
            }
        }
    }

    if ($email !== $vemail) {
        return array('result' => _USERS_EMAILSDIFF, 'errorcode' => 10);
    }
	
    if (!$modvars['reg_verifyemail'] || $modvars['reg_verifyemail'] == 2) {
        if ((isset($pass)) && ("$pass" != "$vpass")) {
            return array('result' => _USERS_PASSDIFFERENT, 'errorcode' => 16);
        } elseif (isset($pass) && (strlen($pass) < $modvars['minpass'])) {
            return array('result' => pnML('_USERS_YOURPASSMUSTBETHISLONG', array('x' => $modvars['minpass'])), 'errorcode' => 15);
        } elseif (empty($pass) && !$modvars['reg_verifyemail']) {
            return array('result' => _USERS_PASSWORDREQUIRED, 'errorcode' => 17);
        }
    }

    if (pnModAvailable('legal')) {
        if ($agreetoterms == 0) {
            return array('result' => _USERS_ERRORMUSTAGREE, 'errorcode' => 3);
        }
    }

    $useragent = strtolower(pnServerGetVar('HTTP_USER_AGENT'));
    $illegaluseragents = $modvars['reg_Illegaluseragents'];
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
            return array('result' => _USERS_USERAGENTBANNED, 'errorcode' => 11);
        }
    }

    $illegaldomains = $modvars['reg_Illegaldomains'];
    if (!empty($illegaldomains)) {
        list($foo, $maildomain) = split('@', $email);
        $maildomain = strtolower($maildomain);
        $disallowed_domains = str_replace(', ', ',', $illegaldomains);
        $checkdisallowed_domains = explode(',', $disallowed_domains);
        if (in_array($maildomain, $checkdisallowed_domains)) {
            return array('result' => _USERS_EMAILDOMAINBANNED, 'errorcode' => 12);
        }
    }

    if (!empty($dynadata) && is_array($dynadata)) {
        $required = Users_ajax_checkrequired($dynadata);
        if (is_array($required) && !empty($required)) {
            return $required;
        }
    }

    if (pnModGetVar('Users', 'reg_question') != '' && pnModGetVar('Users', 'reg_answer') != '') {
        if ($reg_answer != pnModGetVar('Users', 'reg_answer')) {
            return array('result' => _USERS_REGANSWERINCORRECT, 'errorcode' => 14);
        }
    }

    return array('result' => _USERS_NOPROBLEMDETECTED, 'errorcode' => 1);
}

/**
 * Check required dynamic data
 *
 * @access private
 * @author Frank Chestnut
 * @param dynadata - array of user input
 * @return false or mixed array (errorno and fields)
 **/
function Users_ajax_checkrequired($dynadata = array())
{
    if (empty($dynadata)) {
        return false;
    }

    // Delegate check to the right module
    $result = pnModAPIFunc('Profile', 'user', 'checkrequired',
                           array('dynadata' => $dynadata,
                                 'listall' => true));

    // False: no errors
    if ($result === false)
      return $result;

    return array('result' => _USERS_ERRORINREQUIREDFIELDS . ' ('.$result['translatedFieldsStr'].')',
                 'errorcode' => 25,
                 'fields' => $result['fields']);
}
