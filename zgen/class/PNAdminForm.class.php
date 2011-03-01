<?php

class PNAdminForm{

  var $table;
  var $module;
  var $file;
  var $mindmap;
  function __construct($module, $mindmap){
    $this->module = $module;
    $this->mindmap = $mindmap;
    $this->file = "pnadminform.php";
  }

  function createPNAdminFormFile(){


  }
}

?>