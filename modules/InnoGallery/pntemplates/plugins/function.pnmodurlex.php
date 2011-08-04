<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2004, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: function.pnmodurlex.php,v 1.1 2008/12/16 08:51:25 cvsuser Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Template_Plugins
 * @subpackage Functions
 */

/**
 * Smarty function to create a zikula.orgpatible URL for a specific module function.
 *
 * This function returns a module URL string if successful. Unlike the API
 * function pnModURL, this is already sanitized to display, so it should not be
 * passed to the pnvarprepfordisplay modifier.
 *
 * Available parameters:
 *   - modname:  The well-known name of a module for which to create the URL (required)
 *   - type:     The type of function for which to create the URL; currently one of 'user' or 'admin' (default is 'user')
 *   - func:     The actual module function for which to create the URL (default is 'main')
 *   - fragment: The fragement to target within the URL
 *   - ssl:      See below
 *   - fqurl:    Make a fully qualified URL
 *   - append:   (optional) A string to be appended to the URL
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *   - all remaining parameters are passed to the module function
 *
 * Example
 * Create a URL to the News 'view' function with parameters 'sid' set to 3 and 'index' set to '0'
 *   <a href="<!--[pnmodurl modname="News" type="user" func="view" sid="3" index="0"]-->">Link</a>
 *
 * Example SSL
 * Create a secure https:// URL to the News 'view' function with parameters 'sid' set to 3 and 'index' set to '0'
 * ssl - set to constant null,true,false NOTE: $ssl = true not $ssl = 'true'  null - leave the current status untouched, true - create a ssl url, false - create a non-ssl url
 *   <a href="<!--[pnmodurl modname="News" type="user" func="view" sid="3" index="0" ssl=true]-->">Link</a>
 *
 * @author       Mark West
 * @since        08/08/2003
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The URL
 */
function smarty_function_pnmodurlex ($params, &$smarty)
{
    $assign    = isset($params['assign'])                  ? $params['assign']    : null;
    $append    = isset($params['append'])                  ? $params['append']    : '';
    $fragment  = isset($params['fragment'])                ? $params['fragment']  : null;
    $fqurl     = isset($params['fqurl'])                   ? $params['fqurl']     : null;
    $func      = isset($params['func']) && $params['func'] ? $params['func']      : 'main';
    $modname   = isset($params['modname'])                 ? $params['modname']   : null;
    $ssl       = isset($params['ssl'])                     ? (bool)$params['ssl'] : null;
    $type      = isset($params['type']) && $params['type'] ? $params['type']      : 'user';

    // avoid passing these to pnModURL
    unset($params['modname']);
    unset($params['type']);
    unset($params['func']);
    unset($params['fragment']);
    unset($params['ssl']);
    unset($params['fqurl']);
    unset($params['assign']);
    unset($params['append']);

    if (!$modname) {
        $smarty->trigger_error('pnmodurl: parameter [modname] required');
        return false;
    }

    $result = pnModURL($modname, $type, $func, $params, $ssl, $fragment, $fqurl);

    if ($append && is_string($append)) {
        $result .= $append;
    }

    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        //return DataUtil::formatForDisplay($result);
        return $result;
    }
}
