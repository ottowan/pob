<?php
function POBBooking_adminform_main (){
  POBBooking_permission();
  POBBooking_adminform_submit ();
}

  function POBBooking_permission() {
    // Security check
    //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN
    if (!SecurityUtil::checkPermission('POBPortal::', '::', ACCESS_ADMIN)) {
        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
    }
  }


function POBBooking_adminform_submit ()
    {
    POBBooking_permission();

    $forward  = FormUtil::getPassedValue ('forward', null);
    $ctrl     = FormUtil::getPassedValue ('ctrl', 'Booking');
    $reserv_url = pnModURL('POBBooking', 'user', 'form' , array('ctrl'=>'Payment'));
    $validate_form_url = pnModURL('POBBooking', 'user', 'form' , array('ctrl'=>$ctrl));

    $view_url = pnModURL('POBBooking', 'user', 'view' , array('ctrl'=>$ctrl));
    $list_url = pnModURL('POBBooking', 'user', 'list' , array('ctrl'=>$ctrl));
    $success_url = pnModURL('POBBooking', 'user', 'page', array('ctrl'=>'redirect'));
    $form = FormUtil::getPassedValue ('form', null);
    $invalidate_form_url = pnModURL('POBBooking', 'user', 'page', array('ctrl'=>'unsuccess', 'hotel'=>$form['hotelname']));
    if (count($forward)){
      $forward_url = generateUrl($forward);
    }

    if ($_POST['button_cancel'] || $_POST['button_cancel_x']){
      pnRedirect($list_url);
      return true;
    }

    if (!($class = Loader::loadClassFromModule ('POBBooking', $ctrl))){
      v4b_exit ("Unable to load class [$ctrl] ...");
    }
    $object = new $class ();
    $object->getDataFromInput ('form',null,'POST');

    //print_r($object); exit;
    //prepare multi language support
    if (method_exists($object,'prepareLanguageForInput')){
      $object->prepareLanguageForInput();
    }

    
    if (method_exists($object,'validate')){
      if (!$object->validate())
      {
          pnRedirect($invalidate_form_url);
          return true;
      } else  {
        $object->save ();
        //pnRedirect($success_url);
      }
    }
    return true;
}


  function POBBooking_adminform_batch ()
  {
    POBBooking_permission();

    $render = pnRender::getInstance('POBBooking');
    $ctrl    = 'batch';
    $func  =  'form';
    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);
    //echo $ctrl." > ".$func; exit;
    $current = FormUtil::getPassedValue ('current', null);

    $unavailable_url = pnModURL('POBBooking', 'admin', 'form', array('ctrl'=>'batch', 'available'=>'no'));
    $notEnoughtRoom_url = pnModURL('POBBooking', 'admin', 'form', array('ctrl'=>'batch'));

    if(!$_FILES){
      return $render->fetch('admin_form_batch.htm');
    }

    move_uploaded_file($_FILES["files"]["tmp_name"],$_FILES["files"]["name"]); // Copy/Upload CSV
    $objCSV = fopen($_FILES["files"]["name"], "r");
    $data_row = 0;
    $roomstays = array();
    $availabilities = array();
    $alertNoRoom = "";
    $alertEmptyField = "";
    $total_price = 0;
    $i=0;
    $j=0;
    // Check Avlailability
    while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
      if($objArr[0]!="no" & $objArr[1]!=""){
        $checkin = trim($objArr[1]);
        $checkout = trim($objArr[2]);
        $room = trim($objArr[3]);
        $room_amount = trim($objArr[4]);
        $identificational = trim($objArr[5]);
        $nameprefix = trim($objArr[6]);
        $givenname = trim($objArr[7]);
        $surname = trim($objArr[8]);
        $addressline = trim($objArr[9]);
        $cityname = trim($objArr[10]);
        $stateprov = trim($objArr[11]);
        $countryname = trim($objArr[12]);
        $postalcode = trim($objArr[13]);
        $mobile = trim($objArr[14]);
        $phone = trim($objArr[15]);
        $email = trim($objArr[16]);
        $addition_request = trim($objArr[17]);
        $cardcode = trim($objArr[18]);
        $cardnumber = trim($objArr[19]);
        $cardholdername = trim($objArr[20]);
        $card_exp_month = trim($objArr[21]);
        $card_exp_year = trim($objArr[22]);
        $cardsecurecode = trim($objArr[23]);
        $cardbankname = trim($objArr[24]);
        $cardissuingcountry = trim($objArr[25]);
        $url = $current['url'];
        $isocurrency = "THB";

        $emptyField_url = 'admin_'.$func.'_'.strtolower($ctrl).'.htm';
        if(!$room){
          $alertEmptyField = "\"Room type\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$room_amount){
          $alertEmptyField = "\"Number of room\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$identificational){
          $alertEmptyField = "\"Identificational\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$givenname){
          $alertEmptyField = "\"First name\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$surname){
          $alertEmptyField = "\"Last name\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$addressline){
          $alertEmptyField = "\"Address\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$cityname){
          $alertEmptyField = "\"City\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$stateprov){
          $alertEmptyField = "\"State/Province\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$countryname){
          $alertEmptyField = "\"Country\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$postalcode){
          $alertEmptyField = "\"Postal code\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$mobile){
          $alertEmptyField = "\"Mobile Phone\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$email){
          $alertEmptyField = "\"E-mail\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$cardcode){
          $alertEmptyField = "\"Credit card type\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$cardnumber){
          $alertEmptyField = "\"Credit card No.\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$cardholdername){
          $alertEmptyField = "\"Card holder name\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$card_exp_month){
          $alertEmptyField = "\"Card expire month\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$card_exp_year){
          $alertEmptyField = "\"Card expire year\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        if(!$cardsecurecode){
          $alertEmptyField = "\"CVV\" field is Empty in ROW ".($data_row+1).".\\n";
          echo "<script type='text/javascript'>alert('$alertEmptyField')</script>";
          return $render->fetch($emptyField_url);
        }
        $nigth = DateDiff($checkin,$checkout);

////////Get Hotel code API
        $hotelArray = pnModAPIFunc('POBHotel', 'user', 'getHotelCode');
        $hotelcode  = $hotelArray["hotelcode"];

////////GetAvailability API
        $args = array("hotelcode"=>$hotelcode, "startdate"=> $checkin, "enddate"=> $checkout);
        $infomation = pnModAPIFunc('POBRoomSearch', 'user', 'getAvailability', $args);
        $hotelname = $infomation[HotelName];
        if($infomation[Availabilities]){
          $arrAaillable = $infomation[Availabilities];
          foreach($arrAaillable as $key => $value){
            if ($value[InvCode] == $room){
              if($room_amount <= $value[Limit]){
                $availabilities[$i]['invcode'] = $value['InvCode'];
                $availabilities[$i]['date'] = $value['Date'];
                $availabilities[$i]['rate'] = $value['Rate'];

                $room_rate = $value['Rate'];
                $i++;
                $total_price += $value['Rate'];
              }else{
                $alertNoRoom .= "Room ".$value['InvCode']." not available for ".$room_amount." room(s) on ".$value['Date']." in ROW ".($data_row+1).".\\n";
                $j++;
              }
            }
          }
          unset($value);
        }else{
          return pnRedirect($unavailable_url); 
        }
        $room_rate_total = $room_rate*$nigth;
        $roomstays[$data_row]['invcode'] = $room;
        $roomstays[$data_row]['rate'] = $room_rate;
        $roomstays[$data_row]['numberofunits'] = $room_amount;
        $roomstays[$data_row]['startdate'] = $checkin;
        $roomstays[$data_row]['enddate'] = $checkout;
        $roomstays[$data_row]['adult'] = 2;
        $roomstays[$data_row]['child'] = 0;
        $roomstays[$data_row]['night'] = $night;
        $roomstays[$data_row]['room_rate_total'] = $room_rate_total;
        $roomstays[$data_row]['guests'] = 2;

        $data_row++;
      }
    }

    if($alertNoRoom){
      $alertNoRoom .= "\\nPlease check row(s) we mentioned above in CSV file.";
      echo "<script type='text/javascript'>alert('$alertNoRoom')</script>";
      return $render->fetch($emptyField_url);
    }else {
      //// assign value
        $form[] = array(
                      'isocurrency' => $isocurrency,
                      'identificational' => $identificational,
                      'nameprefix' => $nameprefix,
                      'givenname' => $givenname,
                      'surname' => $surname,
                      'addressline' => $addressline,
                      'cityname' => $cityname,
                      'stateprov' => $stateprov,
                      'countryname' => $countryname,
                      'postalcode' => $postalcode,
                      'mobile' => $mobile,
                      'phone' => $phone,
                      'email' => $email,
                      'addition_request' => $addition_request,
                      'profiletype' => "1",
                      'cardcode' => $cardcode,
                      'cardnumber' => $cardnumber,
                      'cardholdername' => $cardholdername,
                      'card_exp_month' => $card_exp_month,
                      'card_exp_year' => $card_exp_year,
                      'cardsecurecode' => $cardsecurecode,
                      'cardbankname' => $cardbankname,
                      'chaincode' => "HT",
                      'hotelcode' => $hotelcode,
                      'hotelname' => $hotelname,
                      'cardbankname' => $cardbankname,
                      'cardissuingcountry' => $data,
                      'total_price' => $total_price,
                      'availabilities' => $availabilities,
                      'roomstays' => $roomstays
                      );
    }

    unset($objArr);
    fclose($objCSV);
    if (!($class = Loader::loadClassFromModule ('POBBooking', 'Customer'))){
      echo "Unable to load class [Customer]...";
    }
    $object = new $class ();
    $object->setData($form);
    unset($form);
    return true;
  }

  function DateDiff($strDate1,$strDate2){
    return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
  }

  function validInput (){
  
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