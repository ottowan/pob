<?php
/**
get current system date
    smarty input 'format' the format string of date
return date string
*/
function smarty_function_modulepath ($params, &$smarty) 
{
  return 'modules/' . pnModGetName(); 
  //return 'modules/InnoCoastalDB'; 
}
?>