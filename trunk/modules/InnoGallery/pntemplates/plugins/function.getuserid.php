<?php

function smarty_function_getuserid($params, &$smarty) {
    $name = $params['name'];
    $assign = $params['assign'];
    if($name){
      $result = DBUtil::selectField('users','uid',"WHERE pn_uname = '$name'");
    }else{
      $result = -1;
    }
    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
