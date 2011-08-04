<?php
/**
get current system date
    smarty input 'format' the format string of date
return date string
*/
function smarty_function_uniqid ($params, &$smarty) 
{
    $prefix = $params['prefix'];
    return uniqid($prefix);
}
?>