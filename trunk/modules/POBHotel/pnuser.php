<?php

Loader::loadClass('SecurityUtilEx', "modules/POBHotel/pnincludes");

/**
* //////////////////////////////////////////////////
* auto execute , for initialize config variable
* this function auto call every page has been fetch
*/
function _autoexecute(){

}

function POBHotel_user_permission()
{
  // Security check
  //we are allow for admin access level , see in config.php variable name ACCESS_EDIT
  //if (!SecurityUtil::checkPermission('POBHotel::', '::', ACCESS_EDIT)) {
  //    LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
  //}
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
  //$render->assign('setting',InnoUtil::getSetting());
  //$render->assign('user',   InnoUtil::getUserInfo());

  if ($lang){
    $render->assign('lang', $lang);
  }else{
    $render->assign('lang', pnUserGetLang());
  }
  $render->assign('access_edit', SecurityUtil::checkPermission('POBHotel::', '::', ACCESS_EDIT));
}

/**
 * Main user function . the user side page controller
 * @author Chayakon PONGSIRI
 * @return string HTML string
 */
function POBHotel_user_main() {
    //_autoexecute();
    //return POBHotel_user_view();
    return POBHotel_user_view();
    //return POBHotel_user_set();
}

/**
* display page with out class loader
*/
function POBHotel_user_page() {
    //_autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('POBHotel');
    
    _preRender($render);
    //try to load class
    $class = Loader::loadClassFromModule ('POBHotel','User' . $ctrl, false);
    if ($class){
      $object  = new $class ();
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
function POBHotel_user_view() {
    //_autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Hotel' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'view' , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', 1 , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 10;
    $render = pnRender::getInstance('POBHotel');
    
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel',$ctrl, false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->get($id);
      if (method_exists($object,'genFilter')){
        $where = $object->genFilter();
      }
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
function POBHotel_user_list() {
    POBHotel_user_permission();
    //_autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Hotel' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');

    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
       
    $render = pnRender::getInstance('POBHotel');

    if (!($class = Loader::loadClassFromModule ('POBHotel',$ctrl, true)))
      return LogUtil::registerError ("Unable to load class [$ctrl] ...");

    $objectArray = new $class ();
    $where   = null;
    $sort = null;
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
      $where = implode(' AND ',$objectArray->_objWhere);
    }else if (is_array($objectArray->_objWhere)){
      $where .= implode(' AND ',$objectArray->_objWhere);
    }else if (!empty($objectArray->_objWhere)){
      $where .= ' AND ' . $objectArray->_objWhere;
    }
    if (empty($sort)){
      $sort = $objectArray->_objSort;
    }


    //Spilt page
    $pager = array();
    $pager['numitems']     = $objectArray->getCount($where , true);
    $pager['itemsperpage'] = $pagesize;
    $render->assign ('startnum', $offset);
    $render->assign ('pager', $pager);
    $objectArray->get ($where, $sort , $offset, $pagesize);

    //assign to view
    $render->assign('objectArray', $objectArray->_objData);

    _preRender($render);

    //var_dump($render);
    //exit;
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
    pnShutDown();
}

function POBHotel_user_form() {
    POBHotel_user_permission();
    //$uid = FormUtil::getPassedValue ('uid', false , 'GET'); //icon | image | video | model
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

      $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
      $render = pnRender::getInstance('POBHotel');
      $mode = null;

      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel',ucfirst($ctrl), false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();


      if ($id && $object){

        $object->get($id);

        $mode = 'edit';  
        $render->assign ('form', $object->_objData);
        //var_dump($object->_objData);
        //exit;
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
* for delete object for database by specify id
*/
function POBHotel_user_delete() {
    POBHotel_user_permission();
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', false , 'GET');
    $forward = FormUtil::getPassedValue ('forward', false , 'GET');

    $uid = FormUtil::getPassedValue ('uid', false , 'GET'); //icon | image | video | model
    $user    = InnoUtil::getUserInfo();

    if ( $id && ((($uid == $user['uid']) && ($uid != '') && $uid ) || (intval($user['level']) == intval(constant('ACCESS_EDIT')) )) ) {
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', ucfirst($ctrl), false)))
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
      if (method_exists($object,'genForward')){
        $forward_url = $object->genForward();
      }else if (!is_array($forward) && $forward){
        $forward_url =  str_replace("&amp;", "&", $forward);
      }else if (is_array($forward)){
        $forward_url = InnoUtil::createUrlFromArray($forward);
      }else{
        $forward_url = pnModURL('POBHotel', 'user', 'list' , array('ctrl'=>$ctrl));
      }
      pnRedirect($forward_url);
      die;
    }
    else{
     $url = pnModURL('Users', 'user', 'loginscreen' , array('ctrl'=>$ctrl) );

      pnRedirect($url);
      die;
    }
}


function POBHotel_user_set() {
    POBHotel_user_permission();
    $user    = InnoUtil::getUserInfo();

    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Developer' , 'GET');
    $func  = FormUtil::getPassedValue ('func', 'set' , 'GET');

    $render = pnRender::getInstance('POBHotel');


    $pntables = pnDBGetTables();
    $devColumn            = $pntables['vote_developer_column'];

    $voteArray = array();

    $sql = "
            SELECT
              $pntables[vote_developer].$devColumn[id],
              $pntables[vote_developer].$devColumn[uid],
              $pntables[vote_developer].$devColumn[firstname],
              $pntables[vote_developer].$devColumn[lastname],
              $pntables[vote_developer].$devColumn[phone],
              $pntables[vote_developer].$devColumn[company],
              $pntables[vote_developer].$devColumn[companyurl],
              $pntables[vote_developer].$devColumn[address],
              $pntables[vote_developer].$devColumn[district],
              $pntables[vote_developer].$devColumn[province],
              $pntables[vote_developer].$devColumn[country],
              $pntables[vote_developer].$devColumn[zipcode]
            FROM
              $pntables[vote_developer]
            WHERE
                $devColumn[uid] = '" . pnVarPrepForStore($user['uid']) . "'
             ";

      $column = array('id', 'uid','firstname', 'lastname','phone', 'company','companyurl', 'address','district', 'province','country', 'zipcode');

      $result = DBUtil::executeSQL($sql);
      $voteArray = DBUtil::marshallObjects ($result, $column);

      if(empty($voteArray)){
        _preRender($render);
        return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
      }else{
        $render->assign ('form', $voteArray[0]);
        _preRender($render);
        //var_dump($voteArray[0]);
        //exit;
        return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
      }


}

function POBHotel_user_ws() {

  Loader::loadClass('POBHotelLoginService', "modules/POBHotel/services");

  $uname      = FormUtil::getPassedValue ('uname');
  $pass       = FormUtil::getPassedValue ('pass');
  $service    = FormUtil::getPassedValue ('service');

  $rest = new POBHotelLoginService();
  
  if(isset($uname) && isset($pass) && $service == "login"){
    $rest->autenticateUser($uname, $pass);
  }

  pnShutDown();

}

