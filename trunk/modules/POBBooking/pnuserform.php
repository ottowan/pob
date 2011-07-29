<?php
function POBBooking_userform_submit ()
{
    $forward  = FormUtil::getPassedValue ('forward', null);
    $ctrl     = FormUtil::getPassedValue ('ctrl', 'POBBooking');
    $with_id  = FormUtil::getPassedValue ('_WITH_INSERT_ID', false);
    $validate_form_url = pnModURL('POBBooking', 'user', 'form' , array('ctrl'=>$ctrl));
    $invalidate_form_url = pnModURL('POBBooking', 'user', 'form' , array('ctrl'=>'POBBooking'));
    $view_url = pnModURL('POBBooking', 'user', 'view' , array('ctrl'=>$ctrl));
    $list_url = pnModURL('POBBooking', 'user', 'list' , array('ctrl'=>$ctrl));
    //$success_url = pnModURL('POBHotel', 'user', 'page', array('ctrl'=>'redirect'));
    //$success_url = pnModURL('POBHotel', 'user', 'list', array('ctrl'=>'price'));

    if (count($forward)){
      $forward_url = generateUrl($forward);
    }

    if ($_POST['button_cancel'] || $_POST['button_cancel_x'])
    {
      pnRedirect($list_url);
      return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl))){
      v4b_exit ("Unable to load class [$ctrl] ...");
    }
    $object = new $class ();
    $object->getDataFromInput ('form',null,'POST');
    //prepare multi language support
    if (method_exists($object,'prepareLanguageForInput')){
      $object->prepareLanguageForInput();
    }


    if (method_exists($object,'validate')){
      if (!$object->validate())
      {
          pnRedirect($invalidate_form_url);
          return true;
      } else {
        $object->save ();
///////////////////////////////////////////////////////////////////////////////////////////////
        $booking = SessionUtil::getVar('pobbooking');
        $i = 1;
        $j = 1;
        $room_name[$i] = '';
        $room_count = 0;
        foreach ($booking as $item){
                   
          if($item[roomtype] != '$room_name[$i]'){
              $room_name[$i] = $item[roomtype];
              
          }else{
              $room_count = 0;
              
          }
          $i++;
          $room_count++;
                    
        }

        $object1 = array('amount_room'   => $amount_room);
        //$object1->getDataFromInput ('form',null,'POST');
        pnModAPIFunc('iHotel', 'user', 'updateRoomReserv',array('form' => $object1));
///////////////////////////////////////////////////////////////////////////////////////////////
        pnRedirect($forward_url);
      }
    }

    return true;
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