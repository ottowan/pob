<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty upper modifier plugin
 *
 * Type:     modifier<br>
 * Name:     base64<br>
 * Purpose:  convert string to base 64
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */

function smarty_modifier_rawurlencode($string)
{
    return rawurlencode($string);
}
?>
