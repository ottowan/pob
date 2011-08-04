<?php
function InnoGallery_userform_main (){
  InnoGallery_userform_submit ();
}

function InnoGallery_userform_submit ()
{
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
        return 'ERROR InnoGallery system can not find controller variable';
      }
    }

    $form_url = pnModURL('InnoGallery', 'user', 'form' , array('ctrl'=>$ctrl));
    $list_url = pnModURL('InnoGallery', 'user', 'list' , array('ctrl'=>$ctrl));

    if ( (isset($_POST['button_cancel']) || isset($_POST['button_cancel_x'])) &&
        empty($_POST['button_submit']))
    {
    	  pnRedirect($list_url);
        return true;
    }

    if (!($class = Loader::loadClassFromModule ('InnoGallery', 'User' . ucfirst($ctrl) , $is_array))){
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
    }
    $object = new $class ();

    if (method_exists($object,'genPermission')){
      $permit = $object->genPermission();
      if (empty($permit)){
        return LogUtil::registerError ("��ǹ���١�ӡѴ�������੾����Ҫԡ��ҹ��");
      }
    }

    $object->getDataFromInput ('form',null,'POST');

    $is_validate = true;

    if(isset($form['captcha'])) {
      $is_validate = pnModAPIFunc('InnoCaptcha', 'user', 'checkCaptchaCode',array('code' => $form['captcha']));
    } else {
      $is_validate = true;
    }

  if($is_validate) {
      SessionUtil::delVar('YLERROR');
      $is_validate = true;
    }else{
      $error = "Invalid security code.";
      SessionUtil::setVar('YLERROR', $error, '/', true, true);
      $is_validate = false;
    }
  
/*
    if (method_exists($object,'validate')){
      $is_validate = $object->validate();
    }
*/
    if ($_POST['button_delete'] || $_POST['button_delete_x']){
        $object->delete ();
    } else if ($is_validate) {
        $object->save ();
    }
    
    if (method_exists($object,'genForward')){
      $forwar_url = $object->genForward();
      if (!empty($forwar_url)){
        pnRedirect($forwar_url);
      }
    }else if (count($forward) > 0){
      $forwar_url = generateUrl($forward);
      pnRedirect($forwar_url);
    }else{
      if(!$is_validate ){
        pnRedirect($form_url); 
      }else{
        pnRedirect($list_url);
      }
    }
    

    return true;
}
/**
* @param $urlArray  the array to generate contain key and value
*                   ex.  array('modname'=>'InnoGallery'
                               'func'   =>'save'
                               'type'   =>'admin');
*/
function generateUrl($urlArray){
  //default value
  $modname = 'InnoGallery';
  $func = 'list';
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
