  <?php
function POBHotel_userform_permission()
{
  // Security check
  //we are allow for admin access level , see in config.php variable name ACCESS_EDIT
  if (!SecurityUtil::checkPermission('POBHotel::', '::', ACCESS_EDIT)) {
      LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
  }
}

  function POBHotel_userform_main (){
    POBHotel_userform_submit();
  }

  function POBHotel_userform_submit ()
  {
      POBHotel_userform_permission();
      $forward =  FormUtil::getPassedValue ('forward', null);
      $ctrl =  FormUtil::getPassedValue ('ctrl', null);
      $form = FormUtil::getPassedValue ('form', null);
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

      $form_url = pnModURL('POBHotel', 'user', 'form' , array('ctrl'=>$ctrl));
      $list_url = pnModURL('POBHotel', 'user', 'list' , array('ctrl'=>$ctrl));

      //var_dump($list_url);
      //exit;
      if ( (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])) &&
          empty($_POST['button_submit']))
      {        
        pnRedirect($list_url);
          return true;
      }

      if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl , $is_array))){
          return LogUtil::registerError ("Unable to load class [$ctrl] ...");
      }
      $object = new $class ();

      if (method_exists($object,'genPermission')){
        $permit = $object->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("Access denied");
        }
      }

      $object->getDataFromInput ('form',null,'POST');


    if (method_exists($object,'validate')){
      if (!$object->validate())
      {
      if (count($forward) > 0){
        $forward_url = generateUrl($forward);
        pnRedirect($forward_url);
      }else{
        pnRedirect($form_url);
      }
   
          return true;
      }
    }

      if ($_POST['button_delete'] || $_POST['button_delete_x']){
          $object->delete ();
      }else{
          $object->save ();
      }
      if (method_exists($object,'genForward')){
        $forwar_url = $object->genForward();
        if (!empty($forwar_url)){
          pnRedirect($forwar_url);
        }
      }else if (count($forward) > 0){
        if($forward[id]){
          $forward[id] =  $forward[id];
        }else{
          $forward[id] = $object->_objData[id];
        }
        $forward_url = generateUrl($forward);
        pnRedirect($forward_url);
      }else{
        pnRedirect($list_url);
      }
      
      return true;
  }

  /**
  * @param $urlArray  the array to generate contain key and value
  *                   ex.  array('modname'=>'POBHotel'
                                 'func'   =>'save'
                                 'type'   =>'admin');
  */
  function generateUrl($urlArray){
    //default value
    $modname = 'POBHotel';
    $func = FormUtil::getPassedValue ('func_forward', 'list');
    $type = 'user';

    $param = array();
    foreach($urlArray as $key => $value){
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
    return "index.php?module=$modname&func=$func&type=$type&" . implode('&',$param);
  }
  ?>