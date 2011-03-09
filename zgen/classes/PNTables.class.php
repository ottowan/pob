<?php
  class PNTables{

    var $table;
    var $module;
    var $file;
    var $mindmap;
    function __construct($module, $mindmap){
      $this->module = $module;
      $this->mindmap = $mindmap;
      $this->file = "pntables.php";
    }

    function setTable($table){
      $this->table = $table;
    }

    function getTable(){
      return $this->table;
    }

    function setModule($module){
      $this->module = $module;
    }

    function getModule(){
      return $this->module;
    }


    function createPNTableFile(){
      $isCreateFile = FileUtil::createFile($this->file);
      if($isCreateFile){
        echo "$this->file Created.<br>";
        //Check node hase field name

          $code .= $this->createHeaderCode();
          foreach($this->mindmap->node->node as $mvc){
            if($mvc->attributes()->TEXT == "models"){

              foreach($mvc->node as $table){
                  if($table->node && $table->node->attributes()->TEXT){
                    echo "Table ::".$table->attributes()->TEXT."<br>";
                    $this->setTable($table);
                    $code .= $this->createBodyCode();
                }
              }//End loop model
            }
          } //End loop mvc 
          $code .= $this->createFooterCode();

        //Create original file & write data to original file
        fwrite($isCreateFile, $code);

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
        /////////////////////////////
        //End of create pntables.php
        ////////////////////////////

      }else{
        echo "File Not Create.<br>";
      }
    }


    function createHeaderCode(){
      $code = "<?php"."\r\n";
      $code .= "  ";
      $code .= "function ".$this->module."_pntables(){"."\r\n";
      $code .= "    ";
      $code .= "//Initialise table array"."\r\n";;
      $code .= "    ";
      $code .= "\$pntable = array();"."\r\n";

      return $code;
    }

    function createBodyCode(){
      $tablename = $this->table->attributes()->TEXT;
      $modulename = $this->module;


      $code .= "    ";
      $code .= "////////////////////////////////////////////"."\r\n";
      $code .= "    ";
      $code .= "//table definition ".strtolower($tablename)."\r\n";
      $code .= "    ";
      $code .= "////////////////////////////////////////////"."\r\n";
      $code .= "    ";
      $code .= "\$pntable['".strtolower($modulename)."_".strtolower($tablename)."'] = DBUtil::getLimitedTablename('".strtolower($modulename)."_".strtolower($tablename)."');"."\r\n";
      $code .= "    ";

      //Loop field name
      $amountFiled = 1;
      $amountFieldIndex = 0;
      $fieldSize = sizeof($this->table->node);
      foreach($this->table->node as $field){
        $fieldName = (string)$field->attributes()->TEXT;
        if($fieldName != "zkjoin" && $fieldName != "zkextend" ){
          //Explode field name & type on mysql
          $value = explode(":", $field->attributes()->TEXT);

          $fieldname = $value[0];
          $fieldtype = $value[1];

          $column .= "                                          ";
          if($amountFiled == $fieldSize){
            $column .= "'".strtolower($fieldname)."' => '".strtolower($tablename)."_".strtolower($fieldname)."'"."\r\n";
          }else{
            $column .= "'".strtolower($fieldname)."' => '".strtolower($tablename)."_".strtolower($fieldname)."',"."\r\n";
          }

          $def .= "                                          ";
          if($fieldname == "id"){
            //Check this filed is last element
            if($amountFiled == $fieldSize){
              $def .="'".strtolower($fieldname)."' => '".MySQLUtil::validateDataType($fieldtype)." NOTNULL AUTOINCREMENT PRIMARY'"."\r\n";
            }else{
              $def .="'".strtolower($fieldname)."' => '".MySQLUtil::validateDataType($fieldtype)." NOTNULL AUTOINCREMENT PRIMARY',"."\r\n";
            }
          }else if( isset($fieldname) && !isset($fieldtype)){
            //Check this filed is last element
            if($amountFiled == $fieldSize){
              $def .="'".strtolower($fieldname)."' => '".MySQLUtil::validateDataType($fieldtype)." DEFAULT NULL'"."\r\n";
            }else{
              $def .="'".strtolower($fieldname)."' => '".MySQLUtil::validateDataType($fieldtype)." DEFAULT NULL',"."\r\n";
            }

            //Split data for check other the id field
            $fieldNameSplit = split('_', strtolower($fieldname));
            $size = sizeOf($fieldNameSplit);
            $lastElement = $size-1;
            //Check index field
            if($size > 1 && $fieldNameSplit[$lastElement] == "id"){
              $tempFieldIndex[$amountFieldIndex] = $fieldname;
              $amountFieldIndex++;
            }

          } else {
            //Check this filed is last element
            
            if($amountFiled == $fieldSize){
              $def .="'".strtolower($fieldname)."' => '".MySQLUtil::validateDataType($fieldtype)." DEFAULT NULL'"."\r\n";
            }else{
              $def .="'".strtolower($fieldname)."' => '".MySQLUtil::validateDataType($fieldtype)." DEFAULT NULL',"."\r\n";
            }

            //Split data for check other the id field
            $fieldNameSplit = split('_', strtolower($fieldname));
            $size = sizeOf($fieldNameSplit);
            $lastElement = $size-1;
            //Check index field
            if($size > 1 && $fieldNameSplit[$lastElement] == "id"){
              $tempFieldIndex[$amountFieldIndex] = $fieldname;
              $amountFieldIndex++;
            }
          }
          $amountFiled++;
        }
      }

      $sizeFieldIndex = sizeOf($tempFieldIndex);
      if($sizeFieldIndex >= 1){
        foreach($tempFieldIndex as $key => $indexField){
          $index .= "                                          ";
          if(($sizeFieldIndex-1) == $key){
            $index .="'idx_".strtolower($tablename)."_".strtolower($indexField)."' =>'".strtolower($indexField)."'"."\r\n";
          }else{
            $index .="'idx_".strtolower($tablename)."_".strtolower($indexField)."' =>'".strtolower($indexField)."',"."\r\n";
          }
        }
      }

      //Set column name
      $code .= "\$pntable['".strtolower($modulename)."_".strtolower($tablename)."_column'] = array("."\r\n";
      $code .= $column;
      $code .= "    ";
      $code .= ");"."\r\n";

      //Set column definition
      $code .= "    ";
      $code .= "\$pntable['".strtolower($modulename)."_".strtolower($tablename)."_column_def'] = array("."\r\n";
      $code .= $def;
      $code .= "    ";
      $code .= ");"."\r\n";

      //Set primary key
      $code .= "    ";
      $code .= "\$pntable['".strtolower($modulename)."_".strtolower($tablename)."_primary_key_column'] = 'id';"."\r\n";

      //Set standard field
      $code .= "    ";
      $code .= "//add standard data fields"."\r\n";
      $code .= "    ";
      $code .= "ObjectUtil::addStandardFieldsToTableDefinition (\$pntable['".strtolower($modulename)."_".strtolower($tablename)."_column'], '".strtolower($tablename)."_');"."\r\n";
      $code .= "    ";
      $code .= "ObjectUtil::addStandardFieldsToTableDataDefinition(\$pntable['".strtolower($modulename)."_".strtolower($tablename)."_column_def']);"."\r\n";

      //Set index field
      if($index){
        $code .= "    ";
        $code .= "\$pntable['".strtolower($modulename)."_".strtolower($tablename)."_column_idx'] = array("."\r\n";
        $code .= $index;
        $code .= "    ";
        $code .= ");";
        $code .= "\r\n";
      }

      $code .= "\r\n";

      return $code;
    }


    function createFooterCode(){
      $code .="   ";
      $code .="return \$pntable;";
      $code .= "\r\n";
      $code .="  ";
      $code .="}"."\r\n";;
      $code .="?>";
      return $code;
    }
/*
          }else if($mvc->attributes()->TEXT == "views"){
            foreach($mvc->node as $view){
              //echo "views ::".$view->attributes()->TEXT."<br>";
            }//End loop view

*/
  }
?>