<?php

class PNImagesGenerator {

  var $module;
  var $mindmap;
  function __construct($module, $mindmap) {
      $this->module = $module;
      $this->mindmap = $mindmap;
  }

  function createPNImagesFile() {

    DirectoryUtil::createDirectory($this->module."/pnimages");

    $filePath = array( 
                      "temp/back.png" => $this->module."/pnimages/back.png",
                      "temp/delete.png" => $this->module."/pnimages/delete.png",
                      "temp/next.png" => $this->module."/pnimages/next.png",
                      "temp/pencil.png" => $this->module."/pnimages/pencil.png"
                      );

    
    //Copy original file to new directory
    foreach($filePath as $key=>$value){
      if (!copy($key, $value)) {
          echo "failed to copy $this->filePath...<br><br>";
      }else{
        echo "Success to copy $this->filePath...<br><br>";
      }
    }
  }



}

?>