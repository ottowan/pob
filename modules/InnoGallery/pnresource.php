<?php
/**
* pndb the db resource connector
*/
error_reporting ( E_ALL & ~E_NOTICE);

require_once('../../config/config.php');
require_once('system/ypFakeDBUtil.class.php');
require_once('system/ypFakeLoader.class.php');
require_once('system/ypFakeObjectUtil.class.php');
require_once('system/ypFakeFormUtil.class.php');
require_once('system/ypcoredb.php');
require_once('pntables.php');
require_once('pnversion.php');
pnresource_main();



function pnresource_main(){
  
  $func = FormUtil::getPassedValue ('func', false , 'GET');
  if (function_exists('pnresource_' .$func)){
    call_user_func('pnresource_' .$func);
  }else{
    die( 'function not exists');
  }
}

function pnresource_getresource(){
  error_reporting ( E_ALL & ~E_NOTICE);

  $db = new YPDBUtil();

  $rstype = FormUtil::getPassedValue ('rstype', 'image' , 'GET'); //icon | image | video | model
  $id = FormUtil::getPassedValue ('id', false , 'GET'); //resource id
  $fieldname = FormUtil::getPassedValue ('fieldname', 'data' , 'GET'); //resource id
  $status = FormUtil::getPassedValue ('status', false , 'GET'); 
  $business_id =  FormUtil::getPassedValue ('business_id', false , 'GET'); 
  $seekat = FormUtil::getPassedValue ('position', false , 'GET');
  
  $table = '';
  $rs = null;
  $filter_field = '';
  switch($rstype){
    case 'icon':
      $table = 'yellowphp_business_icon';
      $tbl_prefix = 'bso_';
      break;
    case 'image':
      $table = 'yellowphp_business_image';
      $tbl_prefix = 'bsi_';
      break;
    case 'video':
      $table = 'yellowphp_business_video';
      $tbl_prefix = 'bsv_';
      break;
    case 'model':
      $table = 'yellowphp_business_model';
      $tbl_prefix = 'bsm_';
      break;
  }
  if ($table && $id){
    $rs = $db->ypselectObjectById($table , $id);
  }
  if ($table && $business_id){
    $rs = $db->ypselectObject($table, 'WHERE ' . $tbl_prefix . 'status = 1 AND ' . $tbl_prefix .'business_id = ' . $business_id);
    if (!$rs){
      $rs = $db->ypselectObject($table, 'WHERE ' . $tbl_prefix . 'status = 0 AND ' . $tbl_prefix .'business_id = ' . $business_id);
    }
  }
  if ($rs){
    $data = null;
    if($seekat != 0 && intval($seekat) > 0) {
      $data = substr($rs[$fieldname],$seekat);
    }else{
      $data = $rs[$fieldname];
    }
    unset($rs[$fieldname]);
    $len = strlen($data);
    $type = '';
    $type = $rs['type'];
    $filename = $rs['filename'];
    // outputing HTTP headers
    //header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    //header('Content-Length: '. $len);
    if ($rstype == 'video'){
      header("Content-Type: video/x-flv");
      if($seekat != 0) {
            print("FLV");
            print(pack('C', 1 ));
            print(pack('C', 1 ));
            print(pack('N', 9 ));
            print(pack('N', 9 ));
      }
    }else{
      header('Content-type: ' . $type);
    }
    echo $data;
  }
  unset($data);
  unset($rs);
  return true;
  
}
