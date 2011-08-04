<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2004, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: function.ypgetbaseurl.php,v 1.1 2008/12/16 08:51:25 cvsuser Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Template_Plugins
 * @subpackage Functions
 */

/**
 * Smarty function to obtain base URL for this site
 *
 * This function obtains the base URL for the site. The base url is defined as the
 * full URL for the site minus any file information  i.e. everything before the
 * 'index.php' from your start page.
 * Unlike the API function ypgetbaseurl, the results of this function are already
 * sanitized to display, so it should not be passed to the pnvarprepfordisplay modifier.
 *
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 *   <!--[ypgetbaseurl]-->
 *
 *
 * @author       Mark West
 * @since        08/08/2003
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the base URL of the site
 */
function smarty_function_ypgetbaseurl ($params, &$smarty)
{
    $assign = isset($params['assign']) ? $params['assign'] : null;
    $result = htmlspecialchars(ypGetBaseURL());

    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
function ypGetBaseURL(){
    $server = pnServerGetVar('SERVER_NAME');
    // IIS sets HTTPS=off
    $https = pnServerGetVar('HTTPS', 'off');
    if ($https != 'off') {
        $proto = 'https://';
    } else {
        $proto = 'http://';
    }

    $path = pnGetBaseURI();

    return "$proto$server$path/";
}