<?php
function POBHotel_ajax_main (){
  POBHotel_permission();
  POBHotel_ajax_submit ();
}

function POBHotel_permission() {
  // Security check
  //we are allow for admin access level , see in config.php variable name ACCESS_COMMENT
  if (!SecurityUtil::checkPermission('POBHotel::', '::', ACCESS_COMMENT)) {
      LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
  }
}

function POBHotel_ajax_form(){
    $ctrl = FormUtil::getPassedValue ('ctrl', false);
    $func  = FormUtil::getPassedValue ('func', 'form' , 'GET');

    if($ctrl){
      $render = pnRender::getInstance('POBHotel');
      $render->assign ('_GET', $_GET);
      $render->assign ('_POST', $_POST);
      $render->assign ('_REQUEST', $_REQUEST);
      
      $render->assign('ctrl', $ctrl);
      if ($lang){
        $render->assign('lang', $lang);
      }else{
        $render->assign('lang', pnUserGetLang());
      }
      
      echo $render->fetch('ajax_'.$func.'_'.strtolower($ctrl).'.htm');
      return true;
    }
    echo "Load form failed!";
    return false;
}

function POBHotel_ajax_submit(){
    //POBHotel_permission();
    $forward =  FormUtil::getPassedValue ('forward', null);
    $ctrl =  FormUtil::getPassedValue ('ctrl', null);
    $form = FormUtil::getPassedValue ('form', null);
    $id = FormUtil::getPassedValue ('id', null);
    //var_dump($form);
    $is_array = FormUtil::getPassedValue ('array', false);
    if ($is_array){
      $is_array = true;
    }else{
      $is_array = false;
    }
    if (empty($ctrl)){
      if ($form[ctrl]){
        $ctrl = $form[ctrl];      
      }else{
        return 'ERROR POBHotel system can not find controller variable';
      }
    }

    //Forward page and select value
    $list_url = pnModURL('POBHotel', 'admin', 'list' , array('ctrl'   => $ctrl));

    if (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])){
        pnRedirect($list_url);
        return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl , $is_array))){
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');
    }
    $object = new $class ();
    $object->getDataFromInput ('form',null,'POST');

    if ($_POST['button_delete'] || $_POST['button_delete_x']){
        $object->delete ();
    }else{
        $object->save ();
    }
    echo "Successfully!";
    return true;
}
function POBHotel_ajax_delete(){
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
      
      echo "<script type=\"text/javascript\">
            $.facebox.close()
            callback_refresh()
            </script>
            Deleted Succesfully!";
      return true;
    }
}

function POBHotel_ajax_list(){
    POBHotel_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'room' , 'GET');
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
    echo  $render->fetch('ajax_'.$func.'_'.strtolower($ctrl).'.htm');
    pnShutDown();
}
?>