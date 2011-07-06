<?php
  /**
  * //////////////////////////////////////////////////
  * auto execute , for initialize config variable
  * this function auto call every page has been fetch
  */
  function POBHotel_permission() {
    // Security check
    //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN
//    if (!SecurityUtil::checkPermission('POBHotel::', '::', ACCESS_ADMIN)) {
//        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
//    }
  }

  /**
  * smarty template auto call before render
  */
  function _languageRender(&$render){

    Loader::loadClass('LanguageUtil');
    $languages = LanguageUtil::getLanguages();
    $currentLanguage = pnUserGetLang();
    $render->assign('languages', $languages);
    $render->assign('currentLanguage', $currentLanguage);
    
  }

  /**
  * Main user function, simply return the index page.
  * @author Parinya Bumrungchoo
  * @return string HTML string
  */
  function POBHotel_admin_main() {
    POBHotel_permission();
    return POBHotel_admin_list();
  }

  /**
  * display page with out class loader
  */
  function POBHotel_admin_page() {
    POBHotel_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('POBHotel');
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object 
  */
  function POBHotel_admin_view() {
    POBHotel_permission();
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

    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
    $render = pnRender::getInstance('POBHotel');

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');

      $object  = new $class ();
      $object->get($id);
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      $render->assign ('view', $object->_objData);
    }
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object Array
  */
  function POBHotel_admin_form() {
    POBHotel_permission();
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

    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
    $render = pnRender::getInstance('POBHotel');
    $mode = null;
    //load class
    if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl, false)))
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
    _languageRender($render);
      return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object Array
  */
  function POBHotel_admin_list() {
    POBHotel_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Province' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');
  
    $is_export = false;
  
    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
    $render = pnRender::getInstance('POBHotel');
  
    //check is export
    $export = FormUtil::getPassedValue ('export', false);
    $button_export = FormUtil::getPassedValue ('button_export', false);
    $button_export_x = FormUtil::getPassedValue ('button_export_x', false);
      if ($export || $button_export || $button_export_x){
      $is_export = true;
    }
  
    $class = Loader::loadClassFromModule ('POBHotel', $ctrl, true);
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
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

      /**
        * for delete object for database by specify id
        */
  function POBHotel_admin_delete() {
    POBHotel_permission();
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    $forward = FormUtil::getPassedValue ('forward', null , 'GET');
  
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');
  
      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();
  
      if($forward){
        $list_url = pnModURL('POBHotel', 'admin', 'list' , array('ctrl'   => $ctrl
                                                                )
                            );
      }else{
        $list_url = pnModURL('POBHotel', 'admin', 'list' , array('ctrl'=>$ctrl
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


  /**
  * Create the room rate
  */
  function POBHotel_admin_createRate() {
    POBHotel_permission();      
    $ctrl = FormUtil::getPassedValue ('ctrl', false);
    $func = FormUtil::getPassedValue ('func', false);
    $form = FormUtil::getPassedValue ('form', false);

    $room_id    = FormUtil::getPassedValue ('room_id', false);
    $season_id  = FormUtil::getPassedValue ('season_id', false);
    $room_rate  = FormUtil::getPassedValue ('room_rate', false);
    $one_bed    = FormUtil::getPassedValue ('one_bed', false);
    $two_bed    = FormUtil::getPassedValue ('two_bed', false);
    $single_bed = FormUtil::getPassedValue ('single_bed', false);


    if($form){
      for($i=0; $i < count($form['room_id'])  ; $i++){
        //$valArray = explode("@", $key);
        $obj = array(
                        'season_id'=>$form['season_id'],
                        'room_id'=>$form['room_id'][$i],
                        'room_rate'=>$form['room_rate'][$i],
                        'one_bed'=>$form['one_bed'][$i],
                        'two_bed'=>$form['two_bed'][$i],
                        'single_bed'=>$form['single_bed'][$i]
                 );

        //Do the insert
        DBUtil::insertObject($obj, 'pobhotel_rate');
        unset($obj);
      }
    }

    $render = pnRender::getInstance('POBHotel');
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }


  function POBHotel_admin_update() {
    POBHotel_permission();      
    $ctrl = FormUtil::getPassedValue ('ctrl', false);
    $func = FormUtil::getPassedValue ('func', false);
    $status = FormUtil::getPassedValue ('status', false);
    $id = FormUtil::getPassedValue ('id', false);


    if($id){
      $pntables = pnDBGetTables();
      $column   = $pntables['pobhotel_member_column'];
      $obj = array('status' => $status);
      $where    = "WHERE $column[id]=".$id;

      DBUTil::updateObject ($obj, 'pobhotel_member', $where);
    }


    if($id == 1){
      POBHotel_admin_createDatabase();
    }

/*
    pnRedirect('admin_list_'.strtolower($ctrl).'.htm');
*/
    $render = pnRender::getInstance('POBHotel');
    _languageRender($render);
    return $render->fetch('admin_list_'.strtolower($ctrl).'.htm');
  }

  function POBHotel_admin_createDatabase(){
    if (!($class = Loader::loadClass('SubdomainCreator', "modules/POBHotel/pnincludes")))
      return LogUtil::registerError ('Unable to load class [SubdomainCreator] ...');
    $table = FormUtil::getPassedValue ('table', false, 'REQUEST');
    $obj = new SubdomainCreator();
    $obj->makedb($table);
    $obj->sqlDump();
    exit;
  }



?>