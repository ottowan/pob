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
            $filePathArray = "PN".ucfirst($table->attributes()->TEXT)."Array.class.php";
            $newFilePathArray = $this->module."/classes/".$filePathArray;

            if($table->node && $table->node->attributes()->TEXT){
              $isCreateFile = FileUtil::createFile($filePath);
              $isCreateFileArray = FileUtil::createFile($filePathArray);
              //Create original file & write data to original file
              $className = "PN".ucfirst($table->attributes()->TEXT);
              $tableName = strtolower($table->attributes()->TEXT);
              $moduleName = strtolower($this->module);
              $joinField = array();
              $extendsionField = array();
              $numberOfLoop = 0;
              echo "Create ".$className."<BR>";
              foreach($table->node as $field){
                if(strstr($field->attributes()->TEXT, "_id") == true){
                  //Split table name
                  $tableNameSplit = split('_id', strtolower($field->attributes()->TEXT));
                  $joinField[$numberOfLoop]['field'] = strtolower($field->attributes()->TEXT);
                  $joinField[$numberOfLoop]['table'] = strtolower($tableNameSplit[0]);

                  echo "Join field : ".$field->attributes()->TEXT."<br>";
                  $numberOfLoop++;
                }
              }

              $class = $this->createClass($className, $tableName, $moduleName, $joinField);
              $classArray = $this->createClassArray($className, $tableName, $moduleName, $joinField);

              fwrite($isCreateFile, $class);
              fwrite($isCreateFileArray, $classArray);

              //Copy original file to new directory
              if (!copy($filePath, $newFilePath)) {
                  echo "failed to copy $filePath...<br>";
              }else{
                echo "Success to copy $filePath...<br>";
                fclose($isCreateFile);
                unlink($filePath);
              }

              //Copy original file to new directory
              if (!copy($filePathArray, $newFilePathArray)) {
                  echo "failed to copy $filePathArray...<br><br>";
              }else{
                echo "Success to copy $filePathArray...<br><br>";
                fclose($isCreateFileArray);
                unlink($filePathArray);
              }

            }else{
              //echo "File ".$filePath." not created.<br>";
            }
          }//End loop model
        }
      }
    }

    function createClassArray($className, $tableName, $moduleName, $joinField=false){

      $text .= "<?php"."\r\n";
      $text .= "  class ".$className."Array extends PNObjectArray {"."\r\n";
      $text .= "    function ".$className."(\$init=null, \$where='') {"."\r\n";
      $text .= "      \$this->PNObject();"."\r\n";
      $text .= "    "."\r\n";
      $text .= "      \$this->_objType       = '".$moduleName."_".$tableName."';"."\r\n";
      $text .= "      \$this->_objField      = 'id';"."\r\n";
      $text .= "      \$this->_objPath       = 'form';"."\r\n";
      $text .= ""."\r\n";

      //Loop joinfield
      if($joinField){
        foreach($joinField as $item){
          $text .= "      \$this->_objJoin[]     = array ( 'join_table'  =>  '".$moduleName."_".$item['table']."',"."\r\n";
          $text .= "                              'join_field'          =>  array('name'),"."\r\n";
          $text .= "                              'object_field_name'   =>  array('".$item['table']."_name'),"."\r\n";
          $text .= "                              'compare_field_table' =>  '".$item['field']."',"."\r\n";
          $text .= "                              'compare_field_join'  =>  'id');"."\r\n";
          $text .= ""."\r\n";
        }
      }

      $text .= "      \$this->_init(\$init, \$where);"."\r\n";
      $text .= "    }"."\r\n";
      $text .= ""."\r\n";

      //Generate genSort() method
      $text .= "    function genSort(){"."\r\n";
      $text .= "      \$order = ' ORDER BY ".$$tableName."_id ASC';"."\r\n";
      $text .= "     return \$order;"."\r\n";
      $text .= "    }"."\r\n";
      $text .= ""."\r\n";

      //Generate genFilter() method
      $text .= "    function genFilter(){"."\r\n";
      $text .= "      //implement code here"."\r\n";
      $text .= "      \$where = '';"."\r\n";
      $text .= "      return \$where;"."\r\n";
      $text .= "    }"."\r\n";

      $text .= "  }"."\r\n";
      $text .= "?>";

      return $text;
    }

    function createClass($className, $tableName, $moduleName, $extendsionField=false){

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