<?php
/**
this plugin for create resource url to br request
@param host     string [optional] ,if crossdomain
@param type     string type = image | model | video | icon
@param size     string size = mini | medium | large
@param id       int resource id
@param business_id the business_id to get resource
@param random   true | false , is random

*/
function smarty_function_resource ($params, &$smarty) 
{
    $host = $params['host'];
    $modname = $params['modname'];
    $type = $params['type'];  //image | model | video | icon
    $size = isset($params['size']) ? '_' . $params['size']: '';  //mini | medium | large , use for type = image , 
    $id   = $params['id'];    //the resource id
    $referer_id = $params['referer_id'];
    $path = $params['path'];
    $random = $params['random']; 
    
    $url = $host . "index.php?module=$modname&func=getresource";

    $url .= '&rstype=' . $type;
    $url .= '&fieldname=data' . $size;
    if (!empty($id)){
      $url .= '&id=' . $id;
    }
    if (!empty($referer_id)){
      $url .= '&referer_id=' . $referer_id;
      $url .= '&status=1';
    }
    if ($path){
      $url .= '&path=' . $path;
    }
    if ($random){
      $url .= '&r=' . uniqid();
    }
    return $url;
}
