<?php

  class PNVersionGenerator{

    var $module;
    var $file;
    var $description;

    function __construct($module, $description){
      $this->module = $module;
      $this->description = $description;
      $this->file = "pnversion.php";
    }


    function createPNVersionFile(){
      $isCreateFile = FileUtil::createFile($this->file);
      if($isCreateFile){
        echo "$this->file Created.<br>";
        $text .= $this->createVersionCode();

        //Create original file & write data to original file
        fwrite($isCreateFile, $text);

        //New file name
        $newfile = $this->module."/".$this->file;

        //Copy original file to new directory
        if (!copy($this->file, $newfile)) {
            echo "failed to copy $this->file...<br><br>";
        }else{
          echo "Success to copy $this->file...<br><br>";
          fclose($isCreateFile);
          unlink($this->file);
        }

      }else{
        echo "File Not Create.<br>";
      }
    }

    function createVersionCode(){
        $code = "<?php";
        $code .= "\r\n";
        $code .= "\$modversion['name'] = '".$this->module."';";
        $code .= "\r\n";
        $code .= "\$modversion['displayname'] = '".$this->module."';";
        $code .= "\r\n";
        $code .= "\$modversion['description'] = '".$this->description." ,This module is generate from ZGen';";
        $code .= "\r\n";
        $code .= "\$modversion['version'] = '1.0';";
        $code .= "\r\n";
        $code .= "\$modversion['credits'] = 'pndocs/credits.txt';";
        $code .= "\r\n";
        $code .= "\$modversion['help'] = 'pndocs/install.txt';";
        $code .= "\r\n";
        $code .= "\$modversion['changelog'] = 'pndocs/changelog.txt';";
        $code .= "\r\n";
        $code .= "\$modversion['license'] = 'pndocs/license.txt';";
        $code .= "\r\n";
        $code .= "\$modversion['official'] = 1;";
        $code .= "\r\n";
        $code .= "\$modversion['author'] = 'Parinya Bumrungchoo';";
        $code .= "\r\n";
        $code .= "\$modversion['contact'] = 'http://www.phuketinnova.com';";
        $code .= "\r\n";
        $code .= "\$modversion['admin'] = 0;";
        $code .= "\r\n";
        $code .= "\$modversion['securityschema'] = array();";
        $code .= "\r\n";
        $code .= "?>";

        return $code;
    }

  }



?>