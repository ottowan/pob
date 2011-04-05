<?php
  /**
  * //////////////////////////////////////////////////
  * auto execute , for initialize config variable
  * this function auto call every page has been fetch
  */
  function Serialize_permission() {
    // Security check
    //we are allow for user access level , see in config.php variable name ACCESS_COMMENT
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

    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    $render->assign('access_edit', true);
  }

  /**
  * Main user function, simply return the index page.
  * @author Parinya Bumrungchoo
  * @return string HTML string
  */
  function Serialize_user_main() {
    Serialize_permission();
    return Serialize_user_list();
  }

  /**
  * display page with out class loader
  */
  function Serialize_user_page() {
    Serialize_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('Serialize');
    _preRender($render);
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object 
  */
  function Serialize_user_view() {
    Serialize_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
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

    $pagesize = pnModGetVar ('Serialize', 'pagesize') ? pnModGetVar ('Serialize', 'pagesize') : 100;
    $render = pnRender::getInstance('Serialize');

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('Serialize', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');

      $object  = new $class ();
      $object->get($id);
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      $render->assign ('view', $object->_objData);
    }
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object Array
  */
  function Serialize_user_form() {
    Serialize_permission();
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
    $step    = FormUtil::getPassedValue ('step', null , 'GET');

    $pagesize = pnModGetVar ('Serialize', 'pagesize') ? pnModGetVar ('Serialize', 'pagesize') : 100;
    $render = pnRender::getInstance('Serialize');
    $mode = null;
    //load class
    if (!($class = Loader::loadClassFromModule ('Serialize', $ctrl, false)))
      return LogUtil::registerError ('Unable to load class [$ctrl] ...');

    $object  = new $class ();
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
  function Serialize_user_list() {
    Serialize_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Province' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');
  
    $is_export = false;
  
    $pagesize = pnModGetVar ('Serialize', 'pagesize') ? pnModGetVar ('Serialize', 'pagesize') : 100;
    $render = pnRender::getInstance('Serialize');
  
    //check is export
    $export = FormUtil::getPassedValue ('export', false);
    $button_export = FormUtil::getPassedValue ('button_export', false);
    $button_export_x = FormUtil::getPassedValue ('button_export_x', false);
      if ($export || $button_export || $button_export_x){
      $is_export = true;
    }
  
    $class = Loader::loadClassFromModule ('Serialize', $ctrl, true);
    if ($class){
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
          $where = $objectArray->_objWhere;
      }
      if (empty($sort)){
        $sort = $objectArray->_objSort;
      }
      //pagnigation variable
      $filter  = FormUtil::getPassedValue ('filter', 0);
      $offset  = FormUtil::getPassedValue ('startnum', 0);
      //$sort    = FormUtil::getPassedValue ('sort', );
      //Split page
      $pagesize = 100;
      $pager = array();
      $pager['numitems']     = $objectArray->getCount ($where , true);
      $pager['itemsperpage'] = $pagesize;
      $render->assign ('startnum', $offset);
      $render->assign ('pager', $pager);
      $objectArray->get ($where, $sort , $offset, $pagesize);
      //assign to view
      $render->assign('objectArray', $objectArray->_objData);
    }
  
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
  }

      /**
        * for delete object for database by specify id
        */
  function Serialize_user_delete() {
    Serialize_permission();
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    $forward = FormUtil::getPassedValue ('forward', null , 'GET');
  
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('Serialize', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');
  
      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();
  
      if($forward){
        $list_url = pnModURL('Serialize', 'user', 'list' , array('ctrl'   => $ctrl
                                                                )
                            );
      }else{
        $list_url = pnModURL('Serialize', 'user', 'list' , array('ctrl'=>$ctrl
                                                                )
      );
      }
  
      if (method_exists($object,'genForward')){
        $forwar_url = $object->genForward();
        pnRedirect($forwar_url);
      }else{
        pnRedirect($list_url);
      }
      die;
    }
  }

?>