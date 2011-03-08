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
              $joinFieldArray = array();
              $extendFieldArray = array();
              $numberOfLoop = 0;
              echo "Create ".$className."<BR>";

              //Loop get all field
              foreach($table->node as $field){
                $fieldName = $field->attributes()->TEXT;
                //var_dump($fieldName); 

                //Check field is join
                if("zkjoin"==strtolower($fieldName)){
                  //var_dump($fieldName); 
                  foreach($field->node as $joinTable){
                    $joinTableName = $joinTable->attributes()->TEXT;
                    //var_dump($joinTableName); 
                    foreach($joinTable->node as $joinField){
                      $joinFieldName = $joinField->attributes()->TEXT;
                      //var_dump($joinFieldName); 
                      $joinFieldArray[(string)$joinTableName][] = strtolower($joinFieldName);
                    }
                  }
                  echo "Join field : ";
                  var_dump($joinFieldArray); 
                }

                //Check field is extend
                if("zkextend"==strtolower($fieldName)){
                  //var_dump($fieldName); 
                  foreach($field->node as $extendTable){
                    $extendTableName = $extendTable->attributes()->TEXT;
                    //var_dump($extendTable); 
                    foreach($extendTable->node as $extendField){
                      $extendFieldName = $extendField->attributes()->TEXT;
                      //var_dump($joinFieldName); 
                      $extendFieldArray[(string)$extendTableName][] = strtolower($extendFieldName);
                    }
                  }
                  echo "Extendsion field: ";
                  var_dump($extendFieldArray); 
                }
              }
              
              $class = $this->createClass($className, $tableName, $moduleName, $extendFieldArray);
              $classArray = $this->createClassArray($className, $tableName, $moduleName, $joinFieldArray);

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

    function createClassArray($className, $tableName, $moduleName, $joinFieldArray=false){

      $text .= "<?php"."\r\n";
      $text .= "  class ".$className."Array extends PNObjectArray {"."\r\n";
      $text .= "    function ".$className."(\$init=null, \$where='') {"."\r\n";
      $text .= "      \$this->PNObject();"."\r\n";
      $text .= "    "."\r\n";
      $text .= "      \$this->_objType       = '".$moduleName."_".$tableName."';"."\r\n";
      $text .= "      \$this->_objField      = 'id';"."\r\n";
      $text .= "      \$this->_objPath       = 'form';"."\r\n";
      $text .= ""."\r\n";

      //Is check has been value
      if($joinFieldArray){
        $loop = 1;

        //Loop join table 
        foreach($joinFieldArray as $keyJoinTable=>$itemJoinTable){
          var_dump($keyJoinTable);
          $text .= "      \$this->_objJoin[]     = array ( 'join_table'  =>  '".$moduleName."_".$keyJoinTable."',"."\r\n";

          $joinField .= "                              'join_field'          =>  array(";
          $objectJoinField .= "                              'object_field_name'   =>  array(";

          $lastKey = end(array_keys($itemJoinTable));
          foreach($itemJoinTable as $keyJoinField => $itemJoinField){

            if ($keyJoinField == $lastKey) {
                // last element
                $joinField .= "'".$itemJoinField."' ";
                $objectJoinField .= "'".$keyJoinTable."_".$itemJoinField."'";
            } else {
                // not last element
                $joinField .= "'".$itemJoinField."', ";
                $objectJoinField .= "'".$keyJoinTable."_".$itemJoinField."',";
            }
          }
          $joinField .= "),"."\r\n";
          $objectJoinField .= "),"."\r\n";
          $text .= $joinField;
          $text .= $objectJoinField;
          $text .= "                              'compare_field_table' =>  '".$keyJoinTable."_id',"."\r\n";
          $text .= "                              'compare_field_join'  =>  'id');"."\r\n";
          $text .= ""."\r\n";

          //Clear value
          $joinField = "";
          $objectJoinField = "";
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

       //Generate footer code
      $text .= "  }"."\r\n";
      $text .= "?>";

      return $text;
    }

    function createClass($className, $tableName, $moduleName, $extendFieldArray=false){

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
      $text .= "    "."\r\n";



      //Is check has been value
      if($extendFieldArray){
        $loop = 1;

        //Generate selectExtendResult() method
        $text .= "    function selectExtendResult(){"."\r\n";
        $text .= "      \$id = FormUtil::getPassedValue ('id', false );"."\r\n";
        $text .= "      \$result = array();"."\r\n";
        $text .= "      if (\$id){"."\r\n";
        //Loop extend table 
        foreach($extendFieldArray as $keyExtendTable=>$itemExtendTable){
          var_dump($keyExtendTable);

          $text .= "        \$result['".$keyExtendTable."'] = DBUtil::selectObjectArray("."\r\n";
          $text .= "                                                     '".$moduleName."_".$keyExtendTable."', "."\r\n";
          $text .= "                                                     'WHERE ".$tableName."_id = \$id' , "."\r\n";
          $text .= "                                                      '', "."\r\n";
          $text .= "                                                      -1, "."\r\n";
          $text .= "                                                      -1,"."\r\n";
          $text .= "                                                      '', "."\r\n";
          $text .= "                                                      null, "."\r\n";
          $text .= "                                                      null, "."\r\n";
          $text .= "                                                      array("."\r\n";

          //Loop extend field
          $lastKey = end(array_keys($itemExtendTable));
          foreach($itemExtendTable as $keyExtendField => $itemExtendField){
            if ($keyExtendField == $lastKey) {
              // last element
              $text .= "                                                            '".$itemExtendField."'"."\r\n";
            } else {
              // not last element
              $text .= "                                                            '".$itemExtendField."',"."\r\n";
            }
          }

        }

        $text .= "                                                            )"."\r\n";
        $text .= "                                                      );"."\r\n";
        $text .= "      }"."\r\n";
        $text .= "      "."\r\n";
        $text .= "      return \$result;"."\r\n";
        $text .= "    }"."\r\n";
      }


      $text .= "  }"."\r\n";
      $text .= "?>";

      return $text;
    }
  }
?>