<?php
/**
get current system date
    smarty input 'format' the format string of date
return date string
*/
function smarty_function_selector_language ($params, &$smarty) 
{
    $module = empty($_GET[module])?pnModGetName():$_GET[module];
    $name = $params[name];
    $type = empty($params[type])?'list':$params[type];
    $lang = empty($params[lang])?pnUserGetLang():$params[lang];

    $langArray = LanguageUtil::getInstalledLanguages();
    if ($type == 'list'){
      return createList($langArray);
      //return "create list";
    }else{
      return createFlag($langArray);
      //return "create flag";
    }

    return "Could not load PNLanguageArray.class.php from $module";
}

function createFlag($langArray){
    $module = empty($_GET[module])?pnModGetName():$_GET[module];
    //generate link
    $get = $_GET;
    $url = null;
    $req = array();
    foreach($get as $key => $value){
      if ((strcmp($key,'lang') === 0) || (strcmp($key,'newlang') === 0)){
        continue;
      }
      $req[] = $key . '=' . $value;
    }
    $url = 'index.php?' .implode('&',$req);
    foreach($langArray as $code => $title){
      $link = $url . '&newlang=' . $code;
      $data .= "<a href='$link'><img src='modules/$module/pnimages/".$code.".png'  alt='$title' border='0'></a>&nbsp;&nbsp;\n";
    }
    return $data;
}

function createList($langArray){
    $script = "<script type=\"text/JavaScript\">
              <!--
              function MM_jumpMenu(targ,selObj,restore){ //v3.0
                eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
                if (restore) selObj.selectedIndex=0;
              }
              //-->
              </script>";
    $data = "<select name='$name' id='$name' onchange=\"MM_jumpMenu('parent',this,0)\">";
    //generate link
    $get = $_GET;
    $url = null;
    $req = array();
    foreach($get as $key => $value){
      if ((strcmp($key,'lang') === 0) || (strcmp($key,'newlang') === 0)){
        continue;
      }
      $req[] = $key . '=' . $value;
    }
    $url = 'index.php?' .implode('&',$req);

    foreach ( $langArray as $code => $title){
      $link = $url . '&newlang=' . $code;
      $currentLang = empty($get[lang])?pnUserGetLang():$get[lang];
      $selected = strcmp($currentLang,$code)===0?"selected='selected'":'';
      $data .= "<option value='$link' $selected>$title</option>\n";
    }
    $data .= "</select>";
    return $script . $data ;
}
?>