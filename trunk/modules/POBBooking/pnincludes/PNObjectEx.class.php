<?php
//load config
//Loader::loadFile('config.php', 'modules/InnoBooking');
class PNObjectEx extends PNObject{

  function prepareLanguageForInput(){
    //language mapping
    $lang = FormUtil::getPassedValue('lang', pnUserGetLang(), 'GET');
    $table = $this->_objType;
    $pntable = &pnDBGetTables();
    $col  = $pntable[$table ."_language"];

    if ($lang && $col){
      foreach($col as $key => $value){
        $this->_objData[$value.'_'.$lang] = &$this->_objData[$value];
      }
    }
  }

  function prepareLanguageForOutput(){
    $table = $this->_objType;
    $pntable = &pnDBGetTables();
    $col  = $pntable[$table ."_language"];
    if ($col){
      $lang = pnUserGetLang();
      $langList = SessionUtil::getVar('LAGUAGE_AVAILABLE');
      foreach($col as $key => $value){
        $this->_objData[$value] = &$this->_objData[$value.'_'.$lang];
      }
    }
  }
}
?>