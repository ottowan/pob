<?php
/**
* smarty function to get constant value which define before
* @param  name    the constant name
* @param  assign  the assign to variable name
* @return string of value or assign to value if assign param is set
*/
function smarty_function_const($params, &$smarty) 
{
    $name   = $params['name'];
    $assign = $params['assign'];
    $value  = constant($name);
    if (isset($assign)) {
        $smarty->assign($assign, $value);
    } else {
        return $value;
    }
    return ;
}