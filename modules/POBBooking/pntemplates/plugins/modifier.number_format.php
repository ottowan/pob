<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_modifier_number_format($string, $decimals = 2, $dec_point = '.' , $thousands_sep = ',')
{
  $string = str_replace(",", "", $string);
  $value = doubleval($string);
  if ($value > 0){
    return number_format(doubleval($string), intval($decimals), $dec_point, $thousands_sep);
  }else{
    return $string;
  }
}
