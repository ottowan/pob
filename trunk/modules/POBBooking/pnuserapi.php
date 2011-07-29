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
?>