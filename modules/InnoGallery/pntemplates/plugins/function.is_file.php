<?php
/**
check has file 
*/
function smarty_function_is_file ($params, &$smarty) 
{
  $file   = $params['file'];
  $path   = $params['path'];
  $path = str_replace('\\', '/', $path);
  $sep = substr($path,-1,1);
  if($sep != '/' || $sep != '\\'){
    $path .= $path . '/';
  }
  $assign = $params['assign'];
  $value = is_file($path . $file);
  if (isset($assign)) {
        $smarty->assign($assign, $value);
  } else {
      return $value;
  }
}
?>