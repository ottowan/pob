<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: user.php,v 1.1 2009/06/26 05:17:22 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
*/

// general
define('_USERS', 'User Account');

// view template
define('_USERS_REGISTER','Register');
define('_USERS_REGISTRATIONANDLOGIN','Log In & Registration');
define('_USERS_RETRIEVEPASS','Recover lost password');
define('_USERS_SELECTOPTION','Please choose an option:');

// loginscreen template
define('_USERS_REMEMBERME', 'Remember me');

// check age template
define('_USERS_CONSENT','(By clicking on the above link you certify that you are either %a% or over, or that you have parental consent to register here.)');
define('_USERS_MUSTBE','You are required to be %a% years of age or older, or to have parental consent to create an account on this site. Please make the appropriate selection below:');
define('_USERS_OVERAGE','I am %a% or over, or I have parental consent.');
define('_USERS_REGISTRATION','New Account Registration');
define('_USERS_UNDERAGE','I am under %a%, and do not have parental consent.');

// registration page
define('_USERS_SUBMITREGISTRATION', 'Submit Registration');
define('_USERS_ALLOWEMAILVIEW','Allow other users to view your e-mail address');
define('_USERS_COOKIEWARNING','Since various features of this site use cookies, it is recommended to have cookies enabled in your browser\'s settings.');
define('_USERS_EMAILAGAIN','E-mail address (repeat for verification)');
define('_USERS_PASSWDAGAIN','Password (repeat for verification)');
define('_USERS_PASSWILLSEND','When registration is completed, your password will be sent to the e-mail address entered below.');
define('_USERS_REGANSWERINCORRECT', 'The answer to the anti-spam sign-up question is incorrect');
define('_USERS_REGNEWUSER','New user registration');
define('_USERS_REGISTRATIONAGREEMENT','I agree to be bound by this site\'s <a href="%touurl%">Terms of Use</a> and <a href="%ppurl%">Privacy Policy</a>');
define('_USERS_REGISTRATIONCHECK', 'Verify Your Entries');
define('_USERS_REQUIREDTEXT','* Indicates a required item.');

// terms of use (tied to the legal module
define('_USERS_CONFIRMTERMSOFUSEHINT', 'The Terms of Use have changed. Please read and accept the new version by activating the following checkbox. If you do not accept then, unfortunately, you will not able to log-in.');
define('_USERS_CONFIRMTERMSOFUSE', 'I accept the <a href="' . pnConfigGetVar('entrypoint', 'index.php') . '?module=Legal&amp;func=termsofuse">Terms of Use</a>');

// finished registration template
define('_USERS_RETURNTOSTART', 'Return to the start page');

// lost password template
define('_USERS_CONFIRMATIONCODE','Confirmation code');
define('_USERS_NOPROBLEM','Please enter EITHER your username OR your e-mail address below and click the \'OK\' button. Then leave this page open. You will be e-mailed a confirmation code. Check your e-mail, retrieve your confirmation code and return to this page: enter EITHER your username OR e-mail address plus the received confirmation code into the form below, and click the \'OK\' button. A new password will be generated and e-mailed to you, and you will then be able to log in with the new password.');
define('_USERS_PASSWORDLOST','Lost Password Recovery');
define('_USERS_SENDPASSWORD','OK');

// login/logout templates
define('_USERS_LOGGINGREDIRECT', 'If you are not automatically redirected, then please click here.');
define('_USERS_LOGGINGYOUIN','You are being logged in. Please wait...');
define('_USERS_YOUARELOGGEDOUT','You have been logged out.');
define('_USERS_YOUARENOTLOGGEDOUT','You have not been logged out.');

// status/error messages
define('_USERS_ACTIVATIONINFO','Use the link in the e-mail to activate your account.');
define('_USERS_AGEREQUIREMENTNOTMET','Error! Sorry, you must be %a% or over, or have parental permission to register here.');
define('_USERS_APPLICATIONRECEIVED', 'Thanks for registering! Your application will be reviewed shortly.');
define('_USERS_CODEMAILED','Code e-mailed for %uname%');
define('_USERS_EMAILDOMAINBANNED','Error! Sorry, this e-mail domain has been banned from registration.');
define('_USERS_EMAILREGISTERED','Error! Sorry, this e-mail address has already been registered');
define('_USERS_EMAILSDIFF','You did not enter the same e-mail address in each box. Please correct and try again.');
define('_USERS_ERRORINREQUIREDFIELDS', 'Error! Sorry, one or more required fields was left blank or incomplete.');
define('_USERS_ERRORMUSTAGREE','Please click on the above checkbox to accept of the Terms of Use and Privacy Statement.');
define('_USERS_INVALIDREGCODE','Error! Sorry, you have entered an invalid registration code');
define('_USERS_LOGININCOMPLETE', 'Login incomplete, please read the note below');
define('_USERS_LOGININCORRECT','Error! Sorry, unrecognized username or password. Please try again...');
define('_USERS_MISSINGUSERNAME','Please enter a username.<br /><a href="javascript:history.back()">Click here to go back</a>.');
define('_USERS_NOPROBLEMDETECTED', 'The information apears valid, please click \'Submit Registration\' to continue.');
define('_USERS_NOTALLOWREG','Sorry! New user registration is currently disabled');
define('_USERS_NOTALLOWREGREASONS','Reasons:');
define('_USERS_NOUSERINFOFOUND','Error! Sorry, no matching user account was found');
define('_USERS_PASSWORDMAILED','Password e-mailed for %uname%');
define('_USERS_PASSWORDREQUIRED','Error! Sorry, please enter a password.');
define('_USERS_PPROFILEMODULENOTAVAILABLE','Error! Sorry, please install the Profile module first!');
define('_USERS_REGISTRATIONFAILED','Error! Sorry, registration failed. Please contact the administrator.');
define('_USERS_USERACTIVATIONFAILED', 'Error! Sorry, your account activation failed. Please contact the administrator');
define('_USERS_USERACTIVATED','Account activated');
define('_USERS_USERAGENTBANNED','Error! Sorry, that user agent is banned');
define('_USERS_USERNAMEINVALID','Sorry that username not acceptable. Please correct and try again.');
define('_USERS_USERNAMENOSPACES','Error! Sorry, your username cannot contain any spaces');
define('_USERS_USERNAMETAKEN','Error! Sorry, this username has already been registered');
define('_USERS_USERNAMERESERVED','Error! Sorry, this username is reserved');
define('_USERS_USERNAMETOOLONG','Error! Sorry, this username is too long. It must be less than 25 characters in length');
define('_USERS_YOUAREREGISTERED','You are now registered. You should receive your user account information, including your password, at the e-mail address you provided.');
define('_USERS_YOURPASSMUSTBETHISLONG','Error! Sorry, your password must be at least %x% characters long');
define('_USERS_ACCOUNTPOSSIBLYINACTIVE', 'If you have just registered here, please check your email to activate your account before logging in.');

// users block - these defines are here because the blockey is 'user'
// hence this file is loaded by the block
define('_USERS_USERSBLOCK', 'Custom block');
define('_USERS_USERSBLOCKCONFIG', 'Configure custom block');
define('_USERS_USERBLOCKENABLEHELP', 'Block content');
define('_USERS_USERBLOCKENABLE', 'Enable your personal menu block');
//define('_USERS_USERBLOCKCONTENTTIP', 'Tip: you can use HTML code to include links to web pages on this site or another site.');
define('_USERS_USERBLOCKUPDATED', 'Done! Custom block updated');
define('_USERS_USERBLOCKMENUFOR', 'Menu for %user%');

//new reg template
define('_USERS_REGTEXT', 'On this site you can easily create your own user profile in only a few simple steps. Once registered you will gain access to more advanced features.');
define('_USERS_REG_STEP1', 'Step 1: Enter your username');
define('_USERS_REG_STEP2', 'Step 2: Enter your e-mail address');
define('_USERS_REG_STEP2_WITHPASSWORD', 'Step 2: Enter your password and your e-mail address');
define('_USERS_REG_STEP3', 'Step 3: Accept the Terms of Use and select your e-mail preference');
define('_USERS_REG_STEP4_ADDITIONALINFO', 'Step 4: Sign-up to this site requires the following additional information');
define('_USERS_REG_STEP4_SPAM', 'Step4: In order to prevent automated sign-ups please answer the following question');
define('_USERS_REG_STEP5_SPAM', 'Step5: In order to prevent automated sign-ups please answer the following question');
define('_USERS_REG_LASTSTEP', 'Finally, check your input and submit');
