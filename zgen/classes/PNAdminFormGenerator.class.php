<?php

class PNAdminFormGenerator{

  var $table;
  var $module;
  var $file;
  var $mindmap;
  function __construct($module, $mindmap) {
      $this->module = $module;
      $this->mindmap = $mindmap;
      $this->filePath = "pnadminform.php";
      $this->newFilePath = $module."/"."pnadminform.php";
  }

  function createPNAdminFormFile() {
      $isCreateFile = FileUtil::createFile($this->filePath);
      if($isCreateFile){
        echo $this->filePath." Created.<br>";
        $text .= $this->createHeaderCode();
        $text .= $this->createMainMethodCode();
        $text .= $this->createPermissionMethodCode();
        $text .= $this->createSubmitMethodCode();
        $text .= $this->createGenerateURLMethodCode();
        $text .= $this->createFooterCode();

        //Create original file & write data to original file
        fwrite($isCreateFile, $text);

        //Copy original file to new directory
        if (!copy($this->filePath, $this->newFilePath)) {
            echo "failed to copy $this->filePath...<br><br>";
        }else{
          echo "Success to copy $this->filePath...<br><br>";
          fclose($isCreateFile);
          unlink($this->filePath);
        }

      }else{
        echo "File Not Create.<br>";
      }
  }


  function createHeaderCode() {
      $code .= "<?php";
      $code .= "\r\n";
      return $code;
  }

  function createPermissionMethodCode() {
    $code .= "  function ".$this->module."_permission() {";$code .= "\r\n";
    $code .= "    // Security check";$code .= "\r\n";
    $code .= "    //we are allow for admin access level , see in config.php variable name ACCESS_COMMENT";$code .= "\r\n";
    $code .= "    if (!SecurityUtil::checkPermission('".$this->module."::', '::', ACCESS_COMMENT)) {";$code .= "\r\n";
    $code .= "        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createMainMethodCode() {
    $code .= "  function ".$this->module."_adminform_main (){";$code .= "\r\n";
    $code .= "    ".$this->module."_permission();";$code .= "\r\n";
    $code .= "    ".$this->module."_adminform_submit ();";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createSubmitMethodCode() {
    $code .= "  function ".$this->module."_adminform_submit()";$code .= "\r\n";
    $code .= "  {";$code .= "\r\n";
    $code .= "    ".$this->module."_permission();";$code .= "\r\n";
    $code .= "    \$forward =  FormUtil::getPassedValue ('forward', null);";$code .= "\r\n";
    $code .= "    \$ctrl =  FormUtil::getPassedValue ('ctrl', null);";$code .= "\r\n";
    $code .= "    \$form = FormUtil::getPassedValue ('form', null);";$code .= "\r\n";
    $code .= "    \$id = FormUtil::getPassedValue ('id', null);";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$is_array = FormUtil::getPassedValue ('array', false);";$code .= "\r\n";
    $code .= "    if (\$is_array){";$code .= "\r\n";
    $code .= "      \$is_array = true;";$code .= "\r\n";
    $code .= "    }else{";$code .= "\r\n";
    $code .= "      \$is_array = false;";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "    if (empty(\$ctrl)){";$code .= "\r\n";
    $code .= "      if (\$form[ctrl]){";$code .= "\r\n";
    $code .= "        \$ctrl = \$form[ctrl];      ";$code .= "\r\n";
    $code .= "      }else{";$code .= "\r\n";
    $code .= "        return 'ERROR ".$this->module." system can not find controller variable';";$code .= "\r\n";
    $code .= "      }";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    //Forward page and select value";$code .= "\r\n";
    $code .= "    \$list_url = pnModURL('".$this->module."', 'admin', 'list' , array('ctrl'   => \$ctrl));";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    if (isset(\$_POST['button_cancel']) || isset(\$_POST['button_cancel_x'])){";$code .= "\r\n";
    $code .= "        pnRedirect(\$list_url);";$code .= "\r\n";
    $code .= "        return true;";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    if (!(\$class = Loader::loadClassFromModule ('".$this->module."', \$ctrl , \$is_array))){";$code .= "\r\n";
    $code .= "        return LogUtil::registerError ('Unable to load class [\$ctrl] ...');";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "    \$object = new \$class ();";$code .= "\r\n";
    $code .= "    \$object->getDataFromInput ('form',null,'POST');";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    if (\$_POST['button_delete'] || \$_POST['button_delete_x']){";$code .= "\r\n";
    $code .= "        \$object->delete ();";$code .= "\r\n";
    $code .= "    }else{";$code .= "\r\n";
    $code .= "        \$object->save ();";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "    if (method_exists(\$object,'genForward')){";$code .= "\r\n";
    $code .= "      \$forward_url = \$object->genForward();";$code .= "\r\n";
    $code .= "      if (!empty(\$forward_url)){";$code .= "\r\n";
    $code .= "        pnRedirect(\$forward_url);";$code .= "\r\n";
    $code .= "      }";$code .= "\r\n";
    $code .= "    }else if (count(\$forward) > 0 && \$forward[id]){";$code .= "\r\n";
    $code .= "      \$forward_url = generateUrl(\$forward);";$code .= "\r\n";
    $code .= "      pnRedirect(\$forward_url);";$code .= "\r\n";
    $code .= "    }else{";$code .= "\r\n";
    $code .= "      pnRedirect(\$list_url);";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "    ";$code .= "\r\n";
    $code .= "    return true;";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createGenerateURLMethodCode() {
    $code .= "  /**";$code .= "\r\n";
    $code .= "  * @param \$urlarray  the array to generate contain key and value";$code .= "\r\n";
    $code .= "  *                   ex.  array('modname'=>'".$this->module."'";$code .= "\r\n";
    $code .= "                                 'func'   =>'save'";$code .= "\r\n";
    $code .= "                                 'type'   =>'admin');";$code .= "\r\n";
    $code .= "  */";$code .= "\r\n";
    $code .= "  function generateUrl(\$urlarray){";$code .= "\r\n";
    $code .= "    //default value";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$forward =  FormUtil::getPassedValue ('forward', null);";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$modname = '".$this->module."';";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    if(\$forward['func']){";$code .= "\r\n";
    $code .= "      \$func = \$forward['func'];";$code .= "\r\n";
    $code .= "    }else{";$code .= "\r\n";
    $code .= "      \$func = 'list';";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    if(\$forward['id']){";$code .= "\r\n";
    $code .= "      \$param[] = 'id='.\$forward['id'];";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$type = 'admin';";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$param = array();";$code .= "\r\n";
    $code .= "    foreach(\$urlarray as \$key => \$value){";$code .= "\r\n";
    $code .= "      if (strcmp(strtolower(\$key),'modname') === 0){";$code .= "\r\n";
    $code .= "        \$modname = \$value;";$code .= "\r\n";
    $code .= "      }else if (strcmp(strtolower(\$key),'func') === 0){";$code .= "\r\n";
    $code .= "        \$func = \$value;";$code .= "\r\n";
    $code .= "      }else if (strcmp(strtolower(\$key),'type') === 0){";$code .= "\r\n";
    $code .= "        \$type = \$value;";$code .= "\r\n";
    $code .= "      }else{";$code .= "\r\n";
    $code .= "        \$param[] = \$key . '=' .\$value;";$code .= "\r\n";
    $code .= "      }";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "    return 'index.php?module=\$modname&func=\$func&type=\$type&' . implode('&',\$param);";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";


    return $code;
  }

  function createFooterCode() {
    $code .="?>";
    return $code;
  }
}

?>