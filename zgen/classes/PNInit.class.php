<?php
  class PNInit{

    var $table;
    var $module;
    var $file;
    var $mindmap;
    function __construct($module, $mindmap){
      $this->module = $module;
      $this->mindmap = $mindmap;
      $this->file = "pninit.php";
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


    function createPNInitFile(){
      $isCreateFile = FileUtil::createFile($this->file);
      if($isCreateFile){
        echo "$this->file Created.<br>";
        //$pntables = new setModule($module);
        $code .= $this->createHeaderCode();
        foreach($this->mindmap->node->node as $mvc){
          if($mvc->attributes()->TEXT == "models"){

            //Create init method
            $code .= $this->createInitMethodHeaderCode();
            foreach($mvc->node as $model){
              //echo "models ::".$model->attributes()->TEXT."<br>";
              if($model->node && $model->node->attributes()->TEXT){
                $this->setTable($model);
                $code .= $this->createInitMethodBodyCode();
              }
            }//End loop model
            $code .= $this->createInitMethodFooterCode();
            $code .= "\r\n";
            //Create delete method
            $code .= $this->createDeleteMethodHeaderCode();
            foreach($mvc->node as $model){
              //echo "models ::".$model->attributes()->TEXT."<br>";
              if($model->node && $model->node->attributes()->TEXT){
                $this->setTable($model);
                $code .= $this->createDeleteMethodBodyCode();
              }
            }//End loop model
            $code .= $this->createDeleteMethodFooterCode();

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
      return $code;
    }

    function createInitMethodHeaderCode(){
      $code .= "  ";
      $code .= "function ".$this->module."_init(){";
      $code .= "\r\n";
      return $code;
    }

    function createInitMethodBodyCode(){
      $tablename = $this->table->attributes()->TEXT;
      $modulename = $this->module;

      $code .= "    ";
      $code .= "if (!DBUtil::createTable('".strtolower($this->module)."_".strtolower($tablename)."')) {";
      $code .= "\r\n";
      $code .= "      ";
      $code .= "return false;";
      $code .= "\r\n";
      $code .= "    ";
      $code .= "}";
      $code .= "\r\n";

      return $code;
    }

    function createInitMethodFooterCode(){
      $code .="    ";
      $code .="return true;";
      $code .= "\r\n";
      $code .="  ";
      $code .="}";
      $code .= "\r\n";
      return $code;
    }


    function createDeleteMethodHeaderCode(){
      $code .= "  ";
      $code .= "function ".strtolower($this->module)."_delete(){";
      $code .= "\r\n";
      return $code;
    }


    function createDeleteMethodBodyCode(){

      $tablename = $this->table->attributes()->TEXT;
      $modulename = $this->module;

      $code .= "    ";
      $code .= "DBUtil::dropTable('".strtolower($this->module)."_".strtolower($tablename)."');";
      $code .= "\r\n";

      return $code;
    }

    function createDeleteMethodFooterCode(){
      $code .="    ";
      $code .="return true;";
      $code .= "\r\n";
      $code .="  ";
      $code .="}";
      return $code;
    }

    function createFooterCode(){
      $code .= "\r\n";
      $code .="?>";
      return $code;
    }

  }


?>