<?php
/**
* for create selector from specify ctrl name
* the parameter sent pass url
* 
* @param ctrl the controller name
* @param filter the where condition [optional]
* @param name the HTML selector name [optional]
* @return string html selector
* @example
* <code>
*    var div_subcate = document.getElementById('subcategory_container');
*    var category = document.getElementById('form_category_id_');
*    var filter = "&filter=scy_category_id = '" + category.value + "'";
*    var name = "&name=form[subcategory_id]";
*    var url = "index.php?module=InnoGallery&type=ajax&func=selector&ctrl=Sub_Category" + filter + name + genSession() ;
*    //alert(url);
*    new Ajax.Request(url, {
*        method:'get',
*        onSuccess: function(transport){
*         var result = transport.responseText;
*         div_subcate.innerHTML= result;
*         }	   
*      });
*    
*  }
* </code>
*/
function InnoGallery_ajax_selector(){
  $ctrl         = FormUtil::getPassedValue ('ctrl', false , 'GET');
  $filter       = FormUtil::getPassedValue ('filter', '1 = 1' , 'GET');
  $name         = FormUtil::getPassedValue ('name', 'selector_' . $ctrl, 'GET');
  $selected     = FormUtil::getPassedValue ('selected', -1234, 'GET');
  $defaultValue = FormUtil::getPassedValue ('defaultValue', 0, 'GET');
  $defaultText  = FormUtil::getPassedValue ('defaultText', '', 'GET');
  $allValue     = FormUtil::getPassedValue ('allValue', 0, 'GET');
  $allText      = FormUtil::getPassedValue ('allText', '', 'GET');


  if (($class = Loader::loadClassFromModule ('InnoGallery', $ctrl, true)) && $ctrl){
    $objectArray = new $class();
    $objectArray->get ($filter);
    
    echo $objectArray->getSelector (strtolower($name) , $selected, $defaultValue, $defaultText,
                          $allValue, $allText);

    
  }
  return true;
}
/**
* for get ajax data for specify ctrl name
* the template name format "ajax_$func_$ctrl.htm"
*/
function InnoGallery_ajax_list() {
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');


    $pagesize = FormUtil::getPassedValue ('pagesize', 100 , 'GET');
    $render = pnRender::getInstance('InnoGallery');
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);

    if ($class = Loader::loadClassFromModule ('InnoGallery', 'Ajax' . ucfirst($ctrl), true)){
      $objectArray = new $class ();
      $where   = null;
      $sort = null;
      if (method_exists($objectArray,'genPermission')){
        $permit = $objectArray->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
        }
      }
      if (method_exists($objectArray,'genFilter')){
        $where = $objectArray->genFilter();
      }
      if (method_exists($objectArray,'genSort')){
        $sort = $objectArray->genSort();
      }
      if (method_exists($objectArray,'selectExtendResult')){
        $resultex = $objectArray->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      if (empty($where)){
        $where = $objectArray->_objWhere;
      }
      if (empty($sort)){
        $sort = $objectArray->_objSort;
      }
      $objectArray->get ($where, $sort , $offset, $pagesize);
      //assign to view
      $render->assign('objectArray', $objectArray->_objData);
    }
    $render->assign('ctrl', $ctrl);
    $render->assign('setting',InnoUtil::getSetting());
    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    echo $render->fetch('ajax_'.$func.'_'.strtolower($ctrl).'.htm');
    return true;
}

function InnoGallery_ajax_view(){
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'view' , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    $where   = '';

    $render = pnRender::getInstance('InnoGallery');
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);
    
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('InnoGallery', 'Ajax' . ucfirst($ctrl), false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();

      if (method_exists($object,'genPermission')){
        $permit = $object->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
        }
      }

      $object->get($id);
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      $render->assign ('view', $object->_objData);
    }
    $render->assign('ctrl', $ctrl);
    $render->assign('setting',InnoUtil::getSetting());
    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    echo $render->fetch('ajax_'.$func.'_'.strtolower($ctrl).'.htm');
    return true;
}
/*
* for save setting value with none redirect
* ex module=InnoGallery&type=ajax&func=save&ctrl=Setting
*    &form[id]=1&form[value]=myval
*/
function InnoGallery_ajax_save(){
  $ctrl =  FormUtil::getPassedValue ('ctrl', null);
  if (!($class = Loader::loadClassFromModule ('InnoGallery', $ctrl))){
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
  }
  $object = new $class ();
  if (method_exists($object,'genPermission')){
    $permit = $object->genPermission();
    if (empty($permit)){
      return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
    }
  }
  $object->getDataFromInput ('form');
  $object->save ();
  return true;
}
/**
* for get server information
* return page title
*/
function InnoGallery_ajax_getServerInfo(){
  Loader::loadFile ('JSON.php', 'includes/classes/JSON');
  $settingArray = InnoUtil::getSetting();
  $result['name'] = pnConfigGetVar('sitename');
  $result['latitude'] = $settingArray['MAP_LATITUDE'];
  $result['longitude'] = $settingArray['MAP_LONGITUDE'];
  $json = new Services_JSON();
  echo $json->encode($result);
  return true;
}

/**
* for delete object for database by specify id
*/
function InnoGallery_ajax_delete() {
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', false , 'GET');
    $forward = FormUtil::getPassedValue ('forward', false , 'GET');

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('InnoGallery','User' . ucfirst($ctrl), false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      if (method_exists($object,'genPermission')){
        $permit = $object->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
        }
      }
      $object->delete ();
      
    }
    return true;
}

/**
scan image directory
*/
function InnoGallery_ajax_getXmlImage(){
  $id      = FormUtil::getPassedValue ('id', false , 'GET');
  if($id){
    
    $result = pnModAPIFunc('InnoGallery', 'user', 'getImage',
                           array( 'id'   => $id ,
                           'path' => IMAGE_LARGE_PATH));
    
    Loader::loadClass('InnoUtil', "modules/InnoGallery/pnincludes");
    $baseurl = InnoUtil::getBaseURL();
    
    print '<?xml version="1.0" encoding="iso-8859-1"?>';
    print '<pics';
    print '>';
    foreach ($result as $val) {
      print '<pic src="'.$baseurl. IMAGE_LARGE_PATH .'/' . $id .'/'.$val.'" title="'.$val.'" />';
    }
    print "</pics>";
  }
  return true;
}
/**
scan image directory for image rotate
*/
function InnoGallery_ajax_getXmlRotateImage(){
  //$id      = FormUtil::getPassedValue ('id', false , 'GET');
  $objectArray = DBUtil::selectObjectArray ('innogallery_albums',  'WHERE abm_count_pictures > 0 AND abm_is_show = 1', 'RAND()');
  $object = $objectArray[0];
  $id = $object['id'];
  if($id){
    Loader::loadClass('InnoUtil', "modules/InnoGallery/pnincludes");
    $baseurl = InnoUtil::getBaseURL();
    //$rand_id = DBUtil::selectFieldArray ('innogallery_albums', 'id', 'WHERE abm_count_pictures > 0', 'RAND()');
    //$object = DBUtil::selectObjectByID ('innogallery_albums', $rand_id[0]);
    $result = pnModAPIFunc('InnoGallery', 'user', 'getImage',
                           array( 'id'   => $id ,
                           'path' => IMAGE_LARGE_PATH));

    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<playlist version="1" xmlns="http://xspf.org/ns/0/">';
    echo '<trackList>';
    
    foreach ($result as $val) {
      echo '<track>';
      echo '  <title>'.$object['title'].'</title>';
      echo '  <creator>'. $object['author'] .'</creator>';
      echo '  <location><![CDATA['.$baseurl . IMAGE_LARGE_PATH. "/$id/". $val .']]></location>';
      //echo '  <info><![CDATA['. $baseurl . pnModUrl('InnoGallery','user','view',array('ctrl'=>'albums','id'=>$id)) .']]></info>';
      //echo '  <link><![CDATA['. 'http://www.google.co.th/' .']]></link>';
      echo '</track>';
    }
    
    echo '  </trackList>';
    echo '</playlist>';
  }
  
  return true;
}