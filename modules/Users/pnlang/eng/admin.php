<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: admin.php,v 1.1 2009/06/26 05:17:21 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package     Zikula_System_Modules
 * @subpackage  Users
*/

// general
define('_USERADMINISTRATION','Users Manager');
define('_USERS_NEWUSER', 'New User');
define('_WARNINGALLFIELDSREQUIRED', 'Reminder: Please type an entry in every box. In the \'Password\' boxes, enter the same password in each box.');

// singular/plural
define('_USERS_USER', 'User');
define('_USERS_USER_LC', 'user');
define('_USERS_USERS', 'Users');

// navigation
define('_USERS_DYNAMICDATA','Account Panel Manager');
define('_USERS_PENDINGAPPLICATIONS', 'Pending Registrations');
define('_MODIFYUSERSCONFIG', 'Users Manager Settings');
define('_SEARCHUSERS', 'Search Users');
define('_USERPROPERTIES', 'User Properties');
define('_USERS_VIEWUSERS', 'View users');
define('_USERS_CREATEUSER', 'Create user');
define('_USERS_MODIFYUSER', 'Modify user');
define('_USERS_DELETEUSER', 'Delete user');
define('_USERS_CONFIRMDELETE', 'Do you really want to delete this user?');

// modify config template
//  general settings
define('_USERS_ANONYMOUSNAME','Name for anonymous user');
define('_USERS_GENERALSETTINGS', 'General Settings');
define('_USERS_HASHMETHOD','Password hashing method (default SHA256)');
define('_USERS_IMAGEPATH','Path to account panel images');
define('_USERS_LOGINVIA', 'Credential required for user log-in');
define('_USERS_UNIQUEMAILADDRESSWARNING', 'If the \'Credential required for user log-in\' is set to \'E-mail address\', then the \'Each e-mail address only registered once\' option below must be set to \'Yes\'.');
define('_USERS_MINAGE','Minimum age (0 = no age check)');
define('_USERS_PASSWDLEN','Minimum length for user passwords');
define('_USERS_SAVELASTLOGINDATE', 'Store user\'s last log-in date in database');
define('_USERS_SPAMQUESTION', 'Spam protection question');
define('_USERS_SPAMQUESTIONHINT', 'Set an individual question to protect against spam-registrations');
define('_USERS_SPAMANSWER', 'Spam protection answer');
define('_USERS_SPAMANSWERHINT', 'Answer for the spam protection question during user registration');

//  registration options
define('_USERS_ALLOWREGISTRATIONS','Allow new user registrations');
define('_USERS_ALLOWREGISTRATIONSDISABLED','Statement displayed if registration disabled');
define('_USERS_ILLEGALDOMAINS', 'Illegal domains (comma-separated)');
define('_USERS_ILLEGALUNAME','Reserved user names (space-separated)');
define('_USERS_ILLEGALUSERAGENTS', 'Banned user agents (comma-separated)');
define('_USERS_REGISTRATIONSETTINGS','User Registration');
define('_USERS_MODERATION','User registration is moderated');
define('_USERS_NOTIFYEMAIL','E-mail address to notify of new user registrations (blank for none)');
define('_USERS_OPTIONALITEMS', 'Show account panel properties');
define('_USERS_UNIQUEEMAIL','Each e-mail address only registered once');
define('_USERS_VERIFYEMAIL','Verify e-mail address during registration ');
define('_USERS_VERIFYEMAIL_YES_ACTIVEMAIL','Yes. User chooses password, then activates account via e-mail');
define('_USERS_VERIFYEMAIL_YES_PASSWORDMAIL','Yes. System-generated password sent directly to e-mail address');
define('_USERS_IDNNAMES','IDN-Domains:');
define('_USERS_IDNNAMESDESC', 'Allow special characters in email addresses and URLs');

//  login options
define('_USERS_LOGINSETTINGS', 'User Log-In Configuration');
define('_USERS_LOGIN_REDIRECT_WCAG', 'WCAG-compliant log-in and log-out');
define('_USERS_LOGIN_REDIRECT_META', 'Use meta-refresh');

// new/modify template
define('_USERS_GROUP', 'Group');
define('_USERS_GROUPMEMBERSHIP', 'Group Membership');
define('_USERS_MEMBEROF', 'Member');

// search template
// Note: all of the strings ending in 'Contains' precede an input field so incomplete sentences here are fine.
define('_USERS_ANYGROUP','Any group');
define('_USERS_CLICKTOFINDALL','To list all users, leave all fields blank');
define('_USERS_REGDATEAFTER','Registration date after (yyyy-mm-dd)');
define('_USERS_REGDATEBEFORE','Registration date before (yyyy-mm-dd)');
define('_USERS_STATUS', 'User status');
define('_USERS_USERGROUPIS','Group membership');
define('_USERS_SEARCHSUBSTRING', 'Partial strings matched with all fields');

// search results
define('_USERS_DESELECTALL','Deselect all');
define('_USERS_MAIL','Mail');
define('_USERS_SELECTALL','Select all');

// mail users template
define('_USERS_FROM','From name');
define('_USERS_MAILUSERS','Mail users');
define('_USERS_MESSAGE','Message');
define('_USERS_REPLYTOADDRESS','Reply-to address');
define('_USERS_SEND_MAIL','Send e-mail message(s)');
define('_USERS_SUBJECT','Subject');

// pending applications template
define('_USERS_VIEWAPPLICATIONS', 'View user applications');
define('_USERS_APPROVEUSERAPPLICATION','Approve new user');
define('_USERS_APPROVEUSERAPPLICATIONBUT','Approve');
define('_USERS_CONFIRMAPPLICATION', 'Confirm action for user application');
define('_USERS_DENYUSERAPPLICATION', 'Deny new user');

// application details template
define('_USERS_VIEWAPPLICATION', 'View user application');

// user statuses
define('_USERS_ACTIVE', 'Active');
define('_USERS_INACTIVE', 'Inactive');
define('_USERS_MUSTACCEPTTOU', 'Inactive until Terms of Use accepted');
define('_USERS_SEARCHRESULTS','Search results');

// error status messages
define('_USERS_MAILSENT','Mail sent');
define('_USERS_MAILSENTFAILED','Error! Sorry! Mail sending failed');
define('_USERS_NOPASS','Error! Sorry! Password is missing');
define('_USERS_NOUSERSELECTED','Error! Sorry! No user(s) have been selected');
