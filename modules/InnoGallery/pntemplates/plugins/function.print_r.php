<?php
/**
set selected value for combo box which match
obj_value
cmp_value
*/
function smarty_function_print_r ($params, &$smarty) 
{
    return print_r($params['value'],true);
    //return var_dump($params['value']);
}
?>