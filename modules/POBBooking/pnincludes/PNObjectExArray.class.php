<?php
//load config
//Loader::loadFile('config.php', 'modules/InnoBooking');
class PNObjectExArray extends PNObjectArray{

  function prepareLanguageForInput(){
    return true;
  }

  function prepareLanguageForOutput(){
    $table = $this->_objType;
    $pntable = &pnDBGetTables();
    $col  = $pntable[$table ."_language"];
    if ($col){
      $lang = pnUserGetLang();
      $langList = SessionUtil::getVar('LAGUAGE_AVAILABLE');
      
      for($i = 0 ; $i < count($this->_objData); $i++){
        foreach($col as $key => $value){
          $this->_objData[$i][$value] = &$this->_objData[$i][$value.'_'.$lang];
        }
      }
    }
  }
}
?>