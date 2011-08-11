<?php
/**
* return image of captcha code
*
*/
function smarty_function_captcha ($params, &$smarty) 
{
  $possible = '23456789bcdfghjkmnpqrstvwxyz';
  $code = '';
  $i = 0;
  while ($i < 5) { 
    $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
    $i++;
  }

  $code = base64_encode($code);

  $img = "<img src='modules/POBBooking/pnincludes/captcha/CaptchaSecurityImages.php?width=100&height=40&code=$code' />";

  SessionUtil::setVar('SECURITY_CAPTCHA', md5($code));
  return $img;
}




?>