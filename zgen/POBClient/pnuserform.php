<?php
  function POBClient_userform_main (){
    POBClient_permission();
    POBClient_userform_submit ();
  }

  function POBClient_permission() {
    // Security check
    //we are allow for user access level , see in config.php variable name ACCESS_COMMENT
    if (!SecurityUtil::checkPermission('POBClient::', '::', ACCESS_COMMENT)) {
        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
    }
  }

  function POBClient_userform_submit()
  {
    POBClient_permission();
    $forward =  FormUtil::getPassedValue ('forward', null);
    $ctrl =  FormUtil::getPassedValue ('ctrl', null);
    $form = FormUtil::getPassedValue ('form', null);
    $id = FormUtil::getPassedValue ('id', null);

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
        return 'ERROR POBClient system can not find controller variable';
      }
    }

    //Forward page and select value
    $list_url = pnModURL('POBClient', 'user', 'list' , array('ctrl'   => $ctrl));

    if (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])){
        pnRedirect($list_url);
        return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBClient', $ctrl , $is_array))){
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');
    }
    $object = new $class ();
    $object->getDataFromInput ('form',null,'POST');

    if ($_POST['button_delete'] || $_POST['button_delete_x']){
        $object->delete ();
    }else{
        $object->save ();
    }
    if (method_exists($object,'genForward')){
      $forward_url = $object->genForward();
      if (!empty($forward_url)){
        pnRedirect($forward_url);
      }
    }else if (count($forward) > 0 && $forward[id]){
      $forward_url = generateUrl($forward);
      pnRedirect($forward_url);
    }else{
      pnRedirect($list_url);
    }
    
    return true;
  }

  /**
  * @param $urlarray  the array to generate contain key and value
  *                   ex.  array('modname'=>'POBClient'
                                 'func'   =>'save'
                                 'type'   =>'user');
  */
  function generateUrl($urlarray){
    //default value

    $forward =  FormUtil::getPassedValue ('forward', null);

    $modname = 'POBClient';

    if($forward['func']){
      $func = $forward['func'];
    }else{
      $func = 'list';
    }

    if($forward['id']){
      $param[] = 'id='.$forward['id'];
    }

    $type = 'user';

    $param = array();
    foreach($urlarray as $key => $value){
      if (strcmp(strtolower($key),'modname') === 0){
        $modname = $value;
      }else if (strcmp(strtolower($key),'func') === 0){
        $func = $value;
      }else if (strcmp(strtolower($key),'type') === 0){
        $type = $value;
      }else{
        $param[] = $key . '=' .$value;
      }
    }
    return 'index.php?module=$modname&func=$func&type=$type&' . implode('&',$param);
  }

?>