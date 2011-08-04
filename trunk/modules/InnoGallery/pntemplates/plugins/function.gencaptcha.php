<?php

/**
* return image of captcha code
*/
function smarty_function_gencaptcha ($params, &$smarty) 
{
    $width = '';
    $height = '';
    if (!empty($params[width])){
      $width = "width='" .$params[width] ."'";
    }
    if (!empty($params[height])){
      $height = "height='" .$params[height] ."'";
    }
    $id =  md5(uniqid(time()));
    $name = uniqid('securimage_');
    $image_url = pnmodurl('InnoGallery','user','getCaptcha'); // pnuser.php
    $img = "<img id='$name' src='$image_url&sid=$id' $width $height align='absmiddle'>";
    return $img;
}

?>
