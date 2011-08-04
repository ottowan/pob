<?php

function smarty_function_ypgetcurrenturl ($params, &$smarty)
{
    $server   = pnGetHost();
    $protocol = pnServerGetProtocol();
    $baseurl  = "$protocol://$server";
    //$request = print_r($params['query'],true);
    $get = $_GET;
    $query = explode('&',$params['query']);
    foreach($query as $q){
      $item = explode('=',$q);
      $key = $item[0];
      $value = $item[1];
      $get[$key] = $value;
    }

    $scriptname = pnServerGetVar('SCRIPT_NAME');
    $pathinfo = pnServerGetVar('PATH_INFO');

    if ($pathinfo == $scriptname) {
      $pathinfo = '';
    }
    //print_r($query);
    //print_r($get);

    $results = array();
    foreach($get as $k => $v){
      if (!empty($k)){
        $results[] .= $k . '=' . $v;
      }
    }
    //print_r($results);
    $result = $baseurl . $scriptname . $pathinfo .'?'. implode('&',$results);
    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
