<?php

function smarty_function_serverpath ($params, &$smarty) 
{
    $full = pathinfo('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']);
    return $full['dirname'];
}
?>