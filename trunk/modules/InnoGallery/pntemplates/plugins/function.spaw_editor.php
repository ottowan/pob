<?php

function smarty_function_spaw_editor ($params, &$smarty) 
{
    Loader::requireOnce('modules/InnoGallery/pnincludes/spaw/spaw.inc.php');
    $name = $params['name'];
    $value = $params['value'];
    $w = $params['width'];
    $h = $params['height'];
    
    $toolbarset = $params['toolbarset'];
    if (!$name){
      $name = "spaw" . rand ( 1 , 99 );
    }
 
    $spaw = new SpawEditor($name,$value);

    if($toolbarset){
      $spaw->setConfigValue('default_toolbarset',$toolbarset); 
    }

    if ($w && $h){
      $spaw->setDimensions($w, $h);
    }
    return $spaw->show();
}
?>