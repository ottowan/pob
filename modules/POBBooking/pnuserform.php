<?php
function POBBooking_userform_submit ()
{
    $forward =  FormUtil::getPassedValue ('forward', null);
    $ctrl =  FormUtil::getPassedValue ('ctrl', null);
    $form = FormUtil::getPassedValue ('form', null);
    
    //$success_url = pnModURL('POBHotel', 'user', 'list', array('ctrl'=>'price'));
	
    $is_array = FormUtil::getPassedValue ('array', false);
//var_dump($is_array);
//var_dump($form);
	//exit;
    if ($is_array) {
        $is_array = true;
    }else {
        $is_array = false;
    }

     if (empty($ctrl)) {
        if ($form[ctrl]) {
            $ctrl = $form[ctrl];
        }else {
            return 'ERROR POBBooking system can not find controller variable';
        }
    }
	//var_dump($ctrl);
	//exit;

    $portal_url = pnModURL('POBPortal', 'user');
    $form_url = pnModURL('POBBooking', 'user', 'form' , array('ctrl'=>$ctrl));
    $list_url = pnModURL('POBBooking', 'user', 'list' , array('ctrl'=>$ctrl));
	  $view_url = pnModURL('POBBooking', 'user', 'view' , array('ctrl'=>$ctrl));
    $success_url = pnModURL('POBBooking', 'user', 'page', 'redirect');

    if ( (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])) &&
            empty($_POST['button_submit'])) {
        pnRedirect($list_url);
        return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl , $is_array))) {
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
    }
    $object = new $class ();

    if (method_exists($object,'genPermission')) {
        $permit = $object->genPermission();
        if (empty($permit)) {
            return LogUtil::registerError ("Access denied");
        }
    }

    $object->getDataFromInput ('form',null,'POST');


    if (method_exists($object,'validate')) {
        if (!$object->validate()) {
            if (count($forward) > 0) {
                $forward_url = generateUrl($forward);
                pnRedirect($forward_url);
            }else {
                pnRedirect($form_url);
            }

            return true;
        }
    }

    if ($_POST['button_delete'] || $_POST['button_delete_x']) {
        $object->delete ();
    }else {
        $object->save ();
        //$forward[status] = "success";
		pnRedirect($success_url);
    }
/*
    if (method_exists($object,'genForward')) {
        $forwar_url = $object->genForward();
        if (!empty($forwar_url)) {
            pnRedirect($forwar_url);
        }
    }else if (count($forward) > 0) {
        if($forward[id]) {
            $forward[id] =  $forward[id];
        }else {
            $forward[id] = $object->_objData[id];
        }
        $forward_url = generateUrl($forward);
        pnRedirect($forward_url);
    }else {
        pnRedirect($list_url);
    }
*/
    return true;

}

function POBBooking_userform_sent (){
    $forward =  FormUtil::getPassedValue ('forward', null);
    $ctrl =  FormUtil::getPassedValue ('ctrl', null);
    $form = FormUtil::getPassedValue ('form', null);
    $view_url = pnModURL('POBBooking', 'user', 'view' , array('ctrl'=>$ctrl));
    $success_url = pnModURL('POBHotel', 'user', 'page', array('ctrl'=>'redirect'));
    //$success_url = pnModURL('POBHotel', 'user', 'list', array('ctrl'=>'price'));

     if (empty($ctrl)) {
        if ($form[ctrl]) {
            $ctrl = $form[ctrl];
        }else {
            return 'ERROR POBBooking system can not find controller variable';
        }
    }

    $form_url = pnModURL('POBBooking', 'user', 'form' , array('ctrl'=>$ctrl));
    $list_url = pnModURL('POBBooking', 'user', 'list' , array('ctrl'=>$ctrl));

    if ( (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])) &&
            empty($_POST['button_submit'])) {
        pnRedirect($list_url);
        return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBBooking', 'User' . $ctrl , $is_array))) {
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
    }
    $object = new $class ();


}

/**
* @param $urlArray  the array to generate contain key and value
*                   ex.  array('modname'=>'InnoTourAgent'
                               'func'   =>'save'
                               'type'   =>'admin');
*/
function generateUrl($urlArray){
  //default value
  $modname = 'POBBooking';
  $func = 'view';
  $type = 'user';
  
  $param = array();
  if (is_array($urlArray)){
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
  return null;
}



?>