<?php
/**
 * Main user function, simply return the tour index page.
 * @author Chayakon PONGSIRI
 * @return string HTML string
 */
function POBBooking_user_main() {
/*
  if (!empty($_SERVER['HTTPS'])){
      return POBBooking_user_form();
  }else {
    //Redirect page
    //$urls = "https" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    //pnRedirect($urls);
    header("HTTP/1.0 404 Not Found");
  }
*/
  
  if(!empty($_POST)){
    return pobbooking_user_form();
  }else{
    //Redirect page
    $url = $_SERVER['PHPSELF'];
    $portal_url = pnModURL('POBPortal', 'user');
    pnRedirect($url);
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
  }
}


/**
* display page with out class loader
*/
function POBBooking_user_page() {

    //_autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Redirect');
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

    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
  if (!empty($_SERVER['HTTPS'])){
  }else {
    //Redirect page
    //$urls = "https" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    //pnRedirect($urls);

    //header("HTTP/1.0 404 Not Found");
  }

}
/**
* display page with class that extend Object 
*/
function POBBooking_user_view() {
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Booking' , 'GET');
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
      $render->assign('lang', pnuserGetLang());
    }
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}
/**
* display page with class that extend Object Array
*/
function POBBooking_user_form() {
  //if (!empty($_SERVER['HTTPS'])){
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Booking' , 'GET');
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
	//var_dump($ctrl);
	//exit;

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
      $render->assign('lang', pnuserGetLang());
    }
	
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
	
  /*}else {
    //Redirect page
    //$urls = "https" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    //pnRedirect($urls);
    header("HTTP/1.0 404 Not Found");
    }*/
}
/**
* display page with class that extend Object Array
*/
function POBBooking_user_list() {
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Booking' , 'GET');
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
      $render->assign('lang', pnuserGetLang());
    }
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}

//create captcha image
function InnoReservation_user_getCaptcha(){
  $result = pnModAPIFunc('InnoCaptcha', 'user', 'createCaptchaImage');
  return true ;
}

?>