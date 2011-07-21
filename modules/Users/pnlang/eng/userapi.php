<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: userapi.php,v 1.1 2009/06/26 05:17:22 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Xiaoyu Huang
 * @package Zikula_System_Modules
 * @subpackage Users
*/

// welcome e-mail
define('_USERS_YOURACCOUNTPENDING', 'Your account application is pending review by the site administrator. Your patience is asked. Confirmation will be e-mailed after review.');
define('_USERS_FOLLOWINGMEM','The information stored about you is as follows:');
define('_USERS_KEEPTHISEMAILSAFE', 'Please keep this e-mail message safe: it is the only record of your password.');
define('_USERS_USEDEMAILTOREGISTER','You or someone else has used your e-mail address (%email%) to register an account at %sitename%.');
define('_USERS_WELCOMESUBJECT','Password for %uname% from %sitename%');
define('_USERS_WELCOMETOSITE', 'Welcome to %sitename% (%siteurl%)!');
define('_USERS_YOURAPPLICATIONAPPROVED', 'Thank you for your understanding. Your account application has been approved. Your password was sent to you in the previous message you received.');
define('_USERS_YOUCANCHANGEITAT','You can change it after you login here %url%');

// password e-mail
define('_USERS_ACCOUNTHASEMAIL','The user account %uname% at %sitename% has this e-mail address associated with it.');
define('_USERS_AWEBUSERHASREQUESTEDPASSWORD','Someone with the IP address %hostname% has just requested that a password be sent.');
define('_USERS_IFYOUDIDNOTASK','If you didn\'t ask for this, don\'t worry. You are seeing this message, not \'them\'. If this was an error just log in with your new password.');
define('_USERS_PASSWORDFOR','Password for %uname%');
define('_USERS_YOURNEWPASSWORDIS','Your new password is: %password%.');

// lost password code e-mail
define('_USERS_CODEFOR','Confirmation code for %uname%');
define('_USERS_AWEBUSERHASREQUESTEDCODE', 'Someone with the IP address %hostname% has just requested a confirmation code to change your password.');
define('_USERS_YOURCODEIS','Your confirmation code is: %code%');
define('_USERS_WITHTHISCODE','With this confirmation code, you can now create a new password by clicking %url%');
define('_USERS_IFYOUDIDNOTASK2','If you didn\'t ask for this, don\'t worry. Just delete this e-mail message.');

// activation e-mail
define('_USERS_ACTIVATIONSUBJECT', 'Activation of %uname%');
define('_USERS_ACIVATEYOURACCOUNTLINK', 'Please click on the following link to complete the registration process.');
define('_USERS_ONCEACTIVATED', 'Once activated, your account details will be as follows:');

// admin notification e-mail
define('_USERS_NOTIFYEMAILSUBJECT','A new user has registered on the site!');
define('_USERS_NOTIFYEMAILBODY','A new member has registered on the site. Member name: %uname% ');

// session expired template
define('_USERS_SESSIONEXPIREDHEADER', 'Your session has expired.');
define('_USERS_SESSIONEXPIREDTEXT', 'For your security, this session has expired because you have been inactive. Please <a href="%u%">log in</a> again to access services.');

