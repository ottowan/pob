<?php
function POBBooking_adminform_main (){
  POBBooking_adminform_submit ();
}

function POBBooking_adminform_submit (){
    //$date_time = getdate();
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
        return 'ERROR POBBooking system can not find controller variable';
      }
    }
    $form_url = pnModURL('POBBooking', 'admin', 'form' , array('ctrl'=>$ctrl));
    $list_url = pnModURL('POBBooking', 'admin', 'list' , array('ctrl'=>$ctrl));


    if (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])){
        pnRedirect($list_url);
        return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl , $is_array))){
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
    }
    $object = new $class ();
    $object->getDataFromInput ('form',null,'POST');

    if (method_exists($object,'validate')){
      if (!$object->validate())
      {
          pnRedirect($form_url);
          return true;
      }
    }

    if ($_POST['button_delete'] || $_POST['button_delete_x']){
        $object->delete ();
    }else{
        $object->save ();
    }
    $step = FormUtil::getPassedValue ('step', null);
    if($step){
      //$render = pnRender::getInstance('Tour');
      $id = $object->_objData['id'];
      $form_url = pnModURL('POBBooking', 'admin', 'form' , array('ctrl'=>$ctrl,'step'=>$step,'hid'=>$id));
      pnRedirect($form_url);
      return true;
    }else if ($forward[step] && $forward[hid] && $forward[ctrl]) {
      $form_url = pnModURL('POBBooking', 'admin', 'form' , array('ctrl'=>$forward[ctrl],'step'=>$forward[step],'hid'=>$forward[hid]));
      pnRedirect($form_url);
      return true;
    }else {
      if (method_exists($object,'genForward')){
        $forwar_url = $object->genForward();
        if (!empty($forwar_url)){
          pnRedirect($forwar_url);
        }
      }else if (count($forward) > 0 && $forward[id]){
        $forwar_url = generateUrl($forward);
        pnRedirect($forwar_url);
      }else{
        pnRedirect($list_url);
      }

      return true;
   }
  }
  
  function generateUrl($urlArray){
      //default value
      //$modname = 'POBBooking';
      //$func = 'view';
      //$type = 'admin';
      
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