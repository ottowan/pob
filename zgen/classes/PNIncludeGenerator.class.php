<?php

class PNIncludeGenerator {

  var $module;
  var $mindmap;
  function __construct($module, $mindmap) {
      $this->module = $module;
      $this->mindmap = $mindmap;
  }

  function createPNIncludeFile() {

      DirectoryUtil::createDirectory($this->module."/pnincludes");

      $filePath = "temp/HtmlUtilEx.class.php";
      $fileName = "HtmlUtilEx.class.php";
      $newFilePath = $this->module."/pnincludes/".$fileName;;

      //Copy original file to new directory
      if (!copy($filePath, $newFilePath)) {
          echo "failed to copy $this->filePath...<br><br>";
      }else{
        echo "Success to copy $this->filePath...<br><br>";
      }
  }



}

?>