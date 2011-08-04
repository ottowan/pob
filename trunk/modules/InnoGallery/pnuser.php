<?php
/**
* //////////////////////////////////////////////////
* auto execute , for initialize config variable
* this function auto call every page has been fetch
*/
function _autoexecute(){

}

/**
* smarty template auto call before render
*/
function _preRender(&$render){
  $lang    = FormUtil::getPassedValue ('lang', false , 'GET');
  $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');

  $render->assign ('_GET', $_GET);
  $render->assign ('_POST', $_POST);
  $render->assign ('_REQUEST', $_REQUEST);

  $render->assign('ctrl', $ctrl);
  $render->assign('user',   InnoUtil::getUserInfo());

  if ($lang){
    $render->assign('lang', $lang);
  }else{
    $render->assign('lang', pnUserGetLang());
  }
  //get page type
  $pagetype = InnoUtil::getPageType();

  $render->assign('pagetype', $pagetype);
  $render->assign('access_edit', SecurityUtil::checkPermission('InnoGallery::', '::', USER_EDIT_LEVEL));
  //check has error message
  $msg_error = SessionUtil::getVar('YLERROR');
  $msg_success = SessionUtil::getVar('YLSUCCESS');

  $render->assign('_ERROR', $msg_error);
  $render->assign('_SUCCESS', $msg_success);
  SessionUtil::delVar('YLERROR');
  SessionUtil::delVar('YLSUCCESS');
}

/**
 * Main user function . the user side page controller
 * @author Chayakon PONGSIRI
 * @return string HTML string
 */
function InnoGallery_user_main() {
    _autoexecute();
    return InnoGallery_user_list();
}
/**
* display page with out class loader
*/
function InnoGallery_user_page() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('InnoGallery');
    
    _preRender($render);
    //try to load class
    $class = Loader::loadClassFromModule ('InnoGallery','User' . ucfirst($ctrl), false);
    if ($class){
      $object  = new $class ();
      if (method_exists($object,'genPermission')){
        $permit = $object->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
        }
      }
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
    }

    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object 
*/
function InnoGallery_user_view() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'view' , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('InnoGallery', 'pagesize') ? pnModGetVar ('InnoGallery', 'pagesize') : 10;
    $render = pnRender::getInstance('InnoGallery');
    
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('InnoGallery','User' . ucfirst($ctrl), false))){
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
      }
      $object  = new $class ();

      if (!SecurityUtilEx::checkPermissionFromObject($object)){
        return LogUtil::registerError ("You not have permission to access this module");
      }
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
    _preRender($render);
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object Array
*/
function InnoGallery_user_form() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'form' , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('InnoGallery', 'pagesize') ? pnModGetVar ('InnoGallery', 'pagesize') : 10;
    $render = pnRender::getInstance('InnoGallery');
    $mode = null;

    //load class
    if (!($class = Loader::loadClassFromModule ('InnoGallery','User' . ucfirst($ctrl), false)))
      return LogUtil::registerError ("Unable to load class [$ctrl] ...");

    $object  = new $class ();

    if (!SecurityUtilEx::checkPermissionFromObject($object)){
      return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
    }
    if (method_exists($object,'genPermission')){
      $permit = $object->genPermission();
      if (empty($permit)){
        return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
      }
    }
    if ($id && $object){

      $object->get($id);

      $mode = 'edit';  
      $render->assign ('form', $object->_objData);
      
    }else{
      $mode = 'new';
    }
    $render->assign ('mode', $mode);
    
    if (method_exists($object,'selectExtendResult')){
      $resultex = $object->selectExtendResult();
      $render->assign('extendResult', $resultex);
    }
    _preRender($render);
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object Array
*/
function InnoGallery_user_list() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'albums' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');
    $is_export = false;


    $pagesize = USER_PAGE_SIZE;
    $render = pnRender::getInstance('InnoGallery');

    //check is export
    $export = FormUtil::getPassedValue ('export', false);
    $button_export = FormUtil::getPassedValue ('button_export', false);
    $button_export_x = FormUtil::getPassedValue ('button_export_x', false);
    if ($export || $button_export || $button_export_x){
      $is_export = true;
    }
    if (!($class = Loader::loadClassFromModule ('InnoGallery','User' . ucfirst($ctrl), true)))
      return LogUtil::registerError ("Unable to load class [$ctrl] ...");

    $objectArray = new $class ();
    if (!SecurityUtilEx::checkPermissionFromObject($object)){
      return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
    }
    if (method_exists($objectArray,'genPermission')){
      $permit = $objectArray->genPermission();
      if (empty($permit)){
        return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
      }
    }
    $where   = null;
    $sort = null;
    if (method_exists($objectArray,'genFilter')){
      $where = $objectArray->genFilter();
    }
    if (method_exists($objectArray,'genSort')){
      $sort = $objectArray->genSort();
    }
    if (method_exists($objectArray,'genLimit')){
      $pagesize = $objectArray->genLimit();
    }
    if (method_exists($objectArray,'selectExtendResult')){
      $resultex = $objectArray->selectExtendResult();
      $render->assign('extendResult', $resultex);
    }
    if (empty($where)){
      $where = implode(' AND ',$objectArray->_objWhere);
    }else if (is_array($objectArray->_objWhere)){
      $where .= implode(' AND ',$objectArray->_objWhere);
    }else if (!empty($objectArray->_objWhere)){
      $where .= ' AND ' . $objectArray->_objWhere;
    }
    if (empty($sort)){
      $sort = $objectArray->_objSort;
    }
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', $sort);

    //Split page
    
    $pager = array();
    $pager['numitems']     = $objectArray->getCount ($where , true);
    $pager['itemsperpage'] = $pagesize;
    $render->assign ('startnum', $offset);
    $render->assign ('pager', $pager);

    $objectArray->get ($where, $sort , $offset, $pagesize);
    //assign to view
    $render->assign('objectArray', $objectArray->_objData);

    _preRender($render);
    if (!$is_export){
      return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
    }else{
      if (empty($mimetype)) { 
         //$mimetype = 'application/octet-stream';
         $mimetype = 'application/vnd.ms-excel';
         //$mimetype = 'text/plain';
      } 
      $result = $render->fetch('admin_export_'.strtolower($ctrl).'.htm');
      $filename = 'plankton_export_'. uniqid('@').'.csv';
      
      // Start sending headers 
      header("Pragma: public"); // required 
      header("Expires: 0"); 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
      header("Cache-Control: private",false); // required for certain browsers 
      header("Content-Transfer-Encoding: binary"); 
      header("Content-Type: " . $mimetype . "; charset=windows-874" ); 
      header("Content-Length: " . strlen($result)); 
      header("Content-Disposition: attachment; filename=\"" . $filename . "\";" ); 
      
      echo $result;
      return true;
    }
}
/**
* for delete object for database by specify id
*/
function InnoGallery_user_delete() {
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', false , 'GET');
    $forward = FormUtil::getPassedValue ('forward', false , 'GET');
    $ctrl_current = FormUtil::getPassedValue ('ctrl_current', false , 'GET');
    $albums_id = FormUtil::getPassedValue ('albums_id', false , 'GET');
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('InnoGallery','User' . ucfirst($ctrl), false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();
      
      if (method_exists($object,'genPermission')){
        $permit = $object->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
        }
      }
      if($forward == 'currentpage'){
        $forward_url = pnModURL('InnoGallery', 'user', 'view' , array('ctrl'=>$ctrl_current,
                                                                      'id'=>$albums_id
                                                                      ));
      }else if (method_exists($object,'genForward')){
        $forward_url = $object->genForward();
      }else if (!is_array($forward) && $forward){
        $forward_url =  str_replace("&amp;", "&", $forward);
      }else if (is_array($forward)){
        $forward_url = InnoUtil::createUrlFromArray($forward);
      }else {
        $forward_url = pnModURL('InnoGallery', 'user', 'list' , array('ctrl'=>$ctrl));
      }
      pnRedirect($forward_url);
      die;
    }
}

/**
* for set/unset status for hot package
* ex. index.php?module=InnoGallery&func=status&ctrl=Tour&id=XXX&status=YYYY
*/
function InnoGallery_user_status() {
  //get status from url 
  $id        = FormUtil::getPassedValue ('id', false , 'GET');
  $status    = FormUtil::getPassedValue ('status', false , 'GET');
  $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
  $field    = FormUtil::getPassedValue ('field', 'status' , 'GET');
  $forward = FormUtil::getPassedValue ('forward', false , 'GET');
  $table = FormUtil::getPassedValue ('table', false , 'GET');
  if ($table && $id){
    //set status
    $result = pnModAPIFunc('InnoGallery', 'user', 'setStatus',
                         array( 'id'     => $id, 
                                'status' => $status,
                                'field'  => $field,
                                'table'  => $table));
  }
  $view_url ='';
  if (!is_array($forward) && $forward){
    $view_url =  str_replace("&amp;", "&", $forward);
  }else if (is_array($forward)){
    $view_url = InnoUtil::createUrlFromArray($forward);
  }else{
    $view_url = pnModURL('InnoGallery', 'user', 'list' , array('ctrl'=>$ctrl));
  }
  pnRedirect($view_url);
  die;
}


/**
 * proxy main function use for gather resource from other host/domain
 * @param   url     a url to request resource (* must encode with 'url encode' algorithm
 *                  that can decode with rawurldecode in PHP command)
 * @param   encode  Is url are encode or not [rawurlencode(default) | base64 | (none)]
 * @example
 *    index.php?module=InnoGallery&type=user&func=proxy&url=<request url>
 * @return string HTML string of resource
 */
function InnoGallery_user_proxy() {
  $url = $_GET['url'];
  $encode = $_GET['encode'];
  if ($encode == 'rawurlencode'){
    $url = rawurldecode($url);
  }else if ($encode == 'base64'){
    $url = base64_decode($url);
  }else if ($encode){
    $url = rawurldecode($url);
  }
  $pos = strpos($url,"?");
  if ($pos){
    $path =  iconv_substr($url,0,strpos($url,"?") + 1);
    $query = iconv_substr($url,strpos($url,"?") + 1,iconv_strlen($url));
    //build query
    $pairs = explode('&', $query);
    $newurl  = '';
    //encode filter[keyword] with base64 before request
    foreach($pairs as $pair) {
        list($name, $value) = explode('=', $pair, 2);
        if ($name == 'filter[keyword]'){
          $value = base64_encode( $value);
          $newurl .= "$name=$value&";
        }else{
          $newurl .= "$name=$value&";
        }
    }
    $url = $path . $newurl;
  }
  if (!empty($url)){

    if ($stream = fopen($url, 'r')) {
        
        echo stream_get_contents($stream);
        fclose($stream);
    }
  }
  return true;
}

function InnoGallery_user_getresource() {
  $rstype = FormUtil::getPassedValue ('rstype', 'image' , 'GET'); //icon | image | video | model
  $id = FormUtil::getPassedValue ('id', false , 'GET'); //resource id
  $fieldname = FormUtil::getPassedValue ('fieldname', 'data' , 'GET'); //resource id
  $status = FormUtil::getPassedValue ('status', false , 'GET'); 
  $referer_id =  FormUtil::getPassedValue ('referer_id', false , 'GET'); 
  $path =  FormUtil::getPassedValue ('path', false , 'GET'); 
  $table = '';
  $rs = null;
  $filter_field = '';
  switch($rstype){
    case 'image':
      $table = 'innoauction_resource_image';
      $tbl_prefix = 'rsi_';
      break;
  }
  if (empty($table)){
    $table = 'innoauction_resource_image';
    $tbl_prefix = 'rsi_';
  }
  if ($table && $id){
    $rs = DBUtil::selectObjectByID($table , $id);
  }
  if ($table && $referer_id){
    $rs = DBUtil::selectObject ($table, 'WHERE ' . 
          $tbl_prefix . "status = 1 AND " . 
          $tbl_prefix . "referer_id = '$referer_id' AND " . 
          $tbl_prefix . "path = '$path'"
          );
    if (!$rs){
      $rs = DBUtil::selectObject ($table, 'WHERE ' . 
          $tbl_prefix . 'status = 0 AND ' . 
          $tbl_prefix . "referer_id = '$referer_id' AND " .
          $tbl_prefix . "path = '$path'"
          );
    }
  }
  if ($rs){
    $len = strlen($rs[$fieldname]);
    $type = $rs['type'];
    // outputing HTTP headers
    header('Content-Length: '. $len);
    header('Content-type: ' . $type);
    
    echo $rs[$fieldname];
  }

  unset($rs);
  return true;
}

function InnoGallery_user_chart() {
  $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
  if ($ctrl){
    $class = Loader::loadClassFromModule ('InnoGallery','Chart' . ucfirst($ctrl), false);
    if ($class){
      $object  = new $class ();
      if (method_exists($object,'genChart')){
        $object->genChart();
      }
    }
  }

  return true;
}

//create captcha image
function InnoGallery_user_getCaptcha(){
  $result = pnModAPIFunc('InnoCaptcha', 'user', 'createCaptchaImage');
  return true ;
}
