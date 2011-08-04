<?php
/**
set selected value for combo box which match
obj_value
cmp_value
*/
function smarty_function_selected ($params, &$smarty) 
{
    $type = $params[type];
    if ($type){
      $result = strcmp(strtolower($params[obj_value]),strtolower($params[cmp_value])) == 0 ? "$type=\"$type\"":"";
    }else{
      $result = strcmp(strtolower($params[obj_value]),strtolower($params[cmp_value])) == 0 ? "selected=\"selected\"":"";
    }
    return $result;
}
?>