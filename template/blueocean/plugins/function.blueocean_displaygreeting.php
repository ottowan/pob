<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2004, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: function.displaygreeting.php,v 1.1 2009/06/26 05:17:19 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Template_Plugins
 * @subpackage Functions
 */

/**
 * Smarty function to display a greeting to the user with the number of private messages received.
 *
 * This function displays a welcome message: welcome and the number of private
 * messages for a registered user or welcome and a signup link for an anonymous user.
 *
 * Examples (with Admin having 5 messages total, 1 unread):
 * <!--[displaygreeting]-->  or
 * <!--[displaygreeting displayMsgs=true]-->
 * Returns
 * Welcome [username]! You have 1 new message.
 * or
 * Welcome [username]! You have no new messages.
 *
 * <!--[displaygreeting class="welcome" displayMsgs=false]-->
 * Returns
 * Welcome [username]!
 * styled with the class "welcome"
 *
 * <!--[displaygreeting multiline=true displayAllMsgs=true]-->
 * Returns
 * Welcome [username]!
 * You have 5 private messages, 1 unread.
 *
 * <!--[displaygreeting displayAllMsgs=false class="messages"]-->
 * Returns
 * Welcome [username]! You have 1 unread message.
 * or
 * Welcome [username]! You have no unread messages.
 *
 * If not logged in, returns
 * Unregistered? <a href="user.php">Register for a user account</a>.
 *
 * @author       Mark West, Martin Stær Andersen
 * @since        19/10/2003
 * @see          function.displaygreeting.php::smarty_function_displaygreeting()
 * @param        array       $params         All attributes passed to this function from the template
 * @param        object      &$smarty        Reference to the Smarty object
 * @param        string      class           CSS class for string
 * @param        string      displayMsgs     Set to false (or any value) to turn off display of Private Messages
 * @param        string      displayAllMsgs  Set to false (or any value) to only display unread Messages
 * @param        string      multiline       Set to true to show Welcome and Messages on two lines (with Break).
 * @return       string      the welcome message
 */
function smarty_function_blueocean_displaygreeting($params, &$smarty)
{
    extract($params);
    unset($params);

    // set some defaults
    if (isset($class)) {
        $class = ' class="'.$class.'"';
    } else {
        $class = '';
    }

    // Turn on message display if not explicitly set or set true, or Display All is set
    $displayMsgs = !isset($displayMsgs) || $displayMsgs || isset($displayAllMsgs) ? true : false;
    $displayAllMsgs = isset($displayAllMsgs) && $displayAllMsgs ? true : false;
    $multiline = isset($multiline) && $multiline ? true : false;

    if (pnUserLoggedIn()) {
        $greeting = '<span'.$class.'>'.pnML('_THEME_WELCOMEUSER', array('username' => pnUserGetVar('uname')));
        $msgmodule = '';
        if (pnModAvailable('pnMessages')) {
            $msgmodule = 'pnMessages';
        } else if (pnModAvailable('InterCom')) {
            $msgmodule = 'InterCom';
        }
        if (!empty($msgmodule) && ($displayMsgs || $displayAllMsgs)) {
            $messages = pnModAPIFunc($msgmodule, 'user', 'getmessagecount');
            $inboxurl = DataUtil::formatForDisplay(pnModURL($msgmodule, 'user', 'inbox'));
            if ($multiline) {
                $greeting .= "<br />\n";
            } else {
                $greeting .= '&nbsp;';
            }
            if ($displayAllMsgs) {
                if ($messages['totalin'] > 0) {
                    $greeting .= pnML('_THEME_YOUHAVEXPRIVATEMESSAGES', array('x' => $messages['totalin'], 'u' => $messages['unread'], 'url' => $inboxurl), true);
                } else {
                    $greeting .= pnML('_THEME_YOUHAVENOPRIVATEMESSAGES', array('url' => $inboxurl), true);
                }
            } else {
                if ($messages['unread'] > 0) {
                    $greeting .= pnML('_THEME_YOUHAVEXNEWPRIVATEMESSAGES', array('x' => $messages['unread'], 'url' => $inboxurl), true);
                } else {
                    $greeting .= pnML('_THEME_YOUHAVENONEWPRIVATEMESSAGES', array('url' => $inboxurl), true);
                }
            }
        }
        $greeting .="</span>\n";
    } else {  // If not logged in, show login link, ask them to register
        //$greeting = '<span'.$class.'>'._CREATEACCOUNT."</span>\n";
		$greeting = '<span'.$class.'>'._CREATEACCOUNT."</span>\n";
    } // end login and private messages

    return $greeting;
}

