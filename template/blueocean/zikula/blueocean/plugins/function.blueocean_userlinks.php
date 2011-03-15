<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2004, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: function.userlinks.php,v 1.1 2009/06/26 05:17:19 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Template_Plugins
 * @subpackage Functions
 */

/**
 * Smarty function to display some user links
 *
 * Example
 * <!--[userlinks start="[" end="]" seperator="|"]-->
 *
 * Two additional defines need adding to a xanthia theme for this plugin
 * _CREATEACCOUNT and _YOURACCOUNT
 *
 * @author       Mark West
 * @since        21/10/03
 * @see          function.userlinks.php::smarty_function_userlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $start       start delimiter
 * @param        string      $end         end delimiter
 * @param        string      $seperator   seperator
 * @return       string      user links
 */
function smarty_function_blueocean_userlinks($params, &$smarty)
{


    if (pnUserLoggedIn()) {
        $links .=" <a href='" . pnModURL('Profile') . "'>" . _YOURACCOUNT . "</a> "; 
        $links .="<a href='" . pnModURL('Users', 'user', 'logout') . "'>"  . _LOGOUT . "</a>";
    } else {
		$links .=" <a href='" . pnModURL('Users', 'user', 'register') . "'>" . _CREATEACCOUNT . "</a> "; 
        $links .="<a href='" . pnModURL('Users', 'user', 'view') . "'>"  . _LOGIN . "</a>";
    }


    return $links;
	//return $links;
	
}
