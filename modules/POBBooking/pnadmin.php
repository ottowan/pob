<?php
/**
* //////////////////////////////////////////////////
* auto execute , for initialize config variable
* this function auto call every page has been fetch
*/
function _autoexecute(){
  // Security check
  //we are allow for admin access level , see in config.php variable name ADMIN_EDIT_LEVEL
  if (!SecurityUtil::checkPermission('POBBooking_::', '::', 800)) {
      LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
  }
}
/**
 * Main user function, simply return the tour index page.
 * @author Chayakon PONGSIRI
 * @return string HTML string
 */
function POBBooking_admin_main() {
    return POBBooking_admin_list();
}
/**
* display page with out class loader
*/
function POBBooking_admin_page() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('POBBooking');
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);
    //set new lang
    if ($lang){
      SessionUtil::setVar('lang', $lang);
    }
    $render->assign('ctrl', $ctrl);
    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object 
*/
function POBBooking_admin_view() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'pobbooking' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'view' , 'GET');
    //$lang enum[eng | jpn | tha]
    $lang    = FormUtil::getPassedValue ('lang', null , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('POBBooking', 'pagesize') ? pnModGetVar ('POBBooking', 'pagesize') : 100;
    $render = pnRender::getInstance('POBBooking');
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);
    //set new lang
    if ($lang){
      SessionUtil::setVar('lang', $lang);
    }
    
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl, false)))
        die ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->get($id);
      //prepare multi language support
      if (method_exists($object,'prepareLanguageForOutput')){
        $object->prepareLanguageForOutput();
      }
      $render->assign ('view', $object->_objData);
    }
    $render->assign('ctrl', $ctrl);
    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object Array
*/
function POBBooking_admin_form() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'pobbooking' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'form' , 'GET');
    //$lang enum[eng | jpn | tha]
    $lang    = FormUtil::getPassedValue ('lang', null , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('POBBooking', 'pagesize') ? pnModGetVar ('POBBooking', 'pagesize') : 100;
    $render = pnRender::getInstance('POBBooking');
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);
    //set new lang
    if ($lang){
      SessionUtil::setVar('lang', $lang);
    }
    $mode = null;
    if ($id){
    //load class
    if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl, false)))
      die ("Unable to load class [$ctrl] ...");

    $object  = new $class ();
    $object->get($id);
    //prepare multi language support
    if (method_exists($object,'prepareLanguageForOutput')){
      $object->prepareLanguageForOutput();
    }
      $mode = 'edit';  
      $render->assign ('form', $object->_objData);
    }else{
      $mode = 'new';
    }
    $render->assign ('mode', $mode);
    $render->assign('ctrl', $ctrl);
    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object Array
*/
function POBBooking_admin_list() {
    _autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Order' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');
    //$lang enum[eng | jpn | tha]
    $lang    = FormUtil::getPassedValue ('lang', null , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('POBBooking', 'pagesize') ? pnModGetVar ('POBBooking', 'pagesize') : 100;
    $render = pnRender::getInstance('POBBooking');
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);
    //set new lang
    if ($lang){
      SessionUtil::setVar('lang', $lang);
    }
    if ($class = Loader::loadClassFromModule ('POBBooking', $ctrl, true)){
      $objectArray = new $class ();

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

      $objectArray->get ($where, $sort , $offset, $pagesize);
      //prepare multi language support
      if (method_exists($objectArray,'prepareLanguageForOutput')){
        $objectArray->prepareLanguageForOutput();
      }
      //assign to view
      $render->assign('objectArray', $objectArray->_objData);
    }
    $render->assign('ctrl', $ctrl);
    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* for delete object for database by specify id
*/
function POBBooking_admin_delete() {
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'pobbooking' , 'GET');
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl, false)))
        die ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();
      $view_url = pnModURL('POBBooking', 'admin', 'list' , array('ctrl'=>$ctrl));
      pnRedirect($view_url);
      die;
    }
}
?>