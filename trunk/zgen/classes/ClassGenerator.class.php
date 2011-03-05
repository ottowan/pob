<?php
  class ClassGenerator{

    var $module;
    var $mindmap;

    function __construct($module, $mindmap) {
        $this->module = $module;
        $this->mindmap = $mindmap;
    }

    function createClassFile() {

      foreach($this->mindmap->node->node as $mvc){
        if($mvc->attributes()->TEXT == "models"){

          DirectoryUtil::createDirectory($this->module."/classes");
          foreach($mvc->node as $table){

            $filePath = "PN".ucfirst($table->attributes()->TEXT).".class.php";
            $newFilePath = $this->module."/classes/".$filePath;
            if($table->node && $table->node->attributes()->TEXT){
              $isCreateFile = FileUtil::createFile($filePath);
              //Create original file & write data to original file

              $className = "PN".ucfirst($table->attributes()->TEXT);
              $tableName = strtolower($table->attributes()->TEXT);
              $moduleName = strtolower($this->module);

              
              $text = $this->createClass($className, $tableName, $moduleName);
              echo "Create ".$className."<BR>";;
              fwrite($isCreateFile, $text);

              //Copy original file to new directory
              if (!copy($filePath, $newFilePath)) {
                  echo "failed to copy $filePath...<br><br>";
              }else{
                echo "Success to copy $filePath...<br><br>";
                fclose($isCreateFile);
                unlink($filePath);
              }
            }else{
              //echo "File ".$filePath." not created.<br>";
            }
          }//End loop model
        }
      }
    }

    function createClass($className, $tableName, $moduleName){
      $text .= "<?php"."\r\n";
      $text .= "  class ".$className." extends PNObject {"."\r\n";
      $text .= "    function ".$className."(\$init=null, \$where='') {"."\r\n";
      $text .= "      \$this->PNObject();"."\r\n";
      $text .= "    "."\r\n";
      $text .= "      \$this->_objType       = '".$moduleName."_".$tableName."';"."\r\n";
      $text .= "      \$this->_objField      = 'id';"."\r\n";
      $text .= "      \$this->_objPath       = 'form';"."\r\n";
      $text .= "    "."\r\n";
      $text .= "      \$this->_init(\$init, \$where);"."\r\n";
      $text .= "    }"."\r\n";
      $text .= "  }"."\r\n";
      $text .= "?>";
       return $text;
    }
  }
?>