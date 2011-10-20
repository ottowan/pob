<?php
/**
* return image of captcha
* ex. $result = pnModAPIFunc('Booking', 'user', 'createCaptchaImage');
*/
function POBBooking_userapi_createCaptchaImage()
{
  include('tool/captcha/securimage.php');
  $img = new securimage();
  $img->ttf_file = "tool/captcha/elephant.ttf";
  $img->image_width = 130;
  $img->image_height = 25;
  $img->code_length = 4;
  $img->font_size = 14;
  $img->show(); // alternate use:  $img->show('/path/to/background.jpg');

  die;
}

/**
* $result = pnModAPIFunc('Booking', 'user', 'checkCaptchaCode',array('code' => 'abcd'));
* @return true if 'CODE' is valid
*/
function POBBooking_userapi_checkCaptchaCode($args)
{
  include('tool/captcha/securimage.php');
  $img = new Securimage();
  $img->ttf_file = "tool/captcha/elephant.ttf";
  $valid = $img->check($args[code]);
  return $valid;
}

function POBBooking_userapi_getAvailability($args) {

  $hotelCode = $args['hotelcode'];
  $startDate = $args['startdate'];
  $endDate   = $args['enddate'];

  if($hotelCode && $startDate && $endDate){

    //Send param to RoomSearch service 
    Loader::loadClass('RoomSearchEndpoint',"modules/POBRoomSearch/pnincludes");
    $roomSearch = new RoomSearchEndpoint();
    $roomSearch->setRoomSearchXML( $hotelCode, $startDate, $endDate );

    //XML Response
    $response = $roomSearch->getRoomSearchXML();

    $xmlObject = simplexml_load_string($response);

    $repackObjectArray = $roomSearch->repackObjectArrayForDisplay($xmlObject);
    
    if(count($repackObjectArray["Properties"]) == 1 && $repackObjectArray){
      $view = $repackObjectArray["Properties"][0];
    }

    $view["startDate"] = $startDate;
    $view["endDate"] = $endDate;

    $issetArray  = true;
  }else{
    $issetArray = false;
  }

  if($issetArray == true){
    return $view;
  }else{
    return null;
  }
}



?>