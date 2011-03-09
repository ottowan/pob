<?php
  class PNTemplateGenerator{

    var $module;
    var $mindmap;

    function __construct($module, $mindmap) {
        $this->module = $module;
        $this->mindmap = $mindmap;
    }

    function createPNTemplateFile() {
      echo "aaa";
      foreach($this->mindmap->node->node as $mvc){
        if($mvc->attributes()->TEXT == "models"){

          DirectoryUtil::createDirectory($this->module."/pntemplates");
          foreach($mvc->node as $table){
            $tableName = strtolower($table->attributes()->TEXT);
            var_dump($tableName);
            if($table->node && $table->node->attributes()->TEXT){


              ///////////////////////////////////////////
              // Generate admin page
              ///////////////////////////////////////////
              //Set form path 
              $formPath = "admin_form_".$tableName.".htm";
              $newFormPath = $this->module."/pntemplates/".$formPath;
              $isCreateFormFile = FileUtil::createFile($formPath);
              echo "Create form : ".$formPath."<BR>";

              //Set list path 
              $listPath = "admin_list_".$tableName.".htm";
              $newListPath = $this->module."/pntemplates/".$listPath;
              $isCreateListFile = FileUtil::createFile($listPath);
              echo "Create list : ".$listPath."<BR>";

              //Set view path 
              $viewPath = "admin_view_".$tableName.".htm";
              $newViewPath = $this->module."/pntemplates/".$viewPath;
              $isCreateViewFile = FileUtil::createFile($viewPath);
              echo "Create view : ".$viewPath."<BR>";



              ///////////////////////////////////////////
              // Generate user page
              ///////////////////////////////////////////
              //Set list path 
              $userListPath = "user_list_".$tableName.".htm";
              $newUserListPath = $this->module."/pntemplates/".$userListPath;
              $isCreateUserListFile = FileUtil::createFile($userListPath);
              echo "Create user list : ".$userListPath."<BR>";

              //Set view path 
              $userViewPath = "user_view_".$tableName.".htm";
              $newUserViewPath = $this->module."/pntemplates/".$userViewPath;
              $isCreateUserViewFile = FileUtil::createFile($userViewPath);
              echo "Create user view : ".$userViewPath."<BR>";

              //Loop get all field
              foreach($table->node as $field){
                $fieldName = $field->attributes()->TEXT;
                var_dump($fieldName); 

                //Check field is join
                if("zkjoin"==strtolower($fieldName)){

                }

                //Check field is extend
                if("zkextend"==strtolower($fieldName)){

                }
              }

              //Get code data for method
              //$class = $this->createClass($className, $tableName, $moduleName, $extendFieldArray);

              //Write the pntemplate admin code to file
              fwrite($isCreateFormFile, $form);
              fwrite($isCreateViewFile, $view);
              fwrite($isCreateListFile, $list);

              //Write the pntemplate user code to file
              fwrite($isCreateUserViewFile, $userView);
              fwrite($isCreateUserListFile, $userList);

              //////////////////////////////////////////
              // Admin
              //////////////////////////////////////////
              //Copy original file to new directory
              if (!copy($formPath, $newFormPath)) {
                  echo "failed to copy $viewPath...<br><br>";
              }else{
                echo "Success to copy $viewPath...<br><br>";
                fclose($isCreateFormFile);
                unlink($formPath);
              }
              //Copy original file to new directory
              if (!copy($viewPath, $newViewPath)) {
                  echo "failed to copy $viewPath...<br>";
              }else{
                echo "Success to copy $viewPath...<br>";
                fclose($isCreateViewFile);
                unlink($viewPath);
              }
              //Copy original file to new directory
              if (!copy($listPath, $newListPath)) {
                  echo "failed to copy $listPath...<br>";
              }else{
                echo "Success to copy $listPath...<br>";
                fclose($isCreateListFile);
                unlink($listPath);
              }

              //////////////////////////////////////////
              // User
              //////////////////////////////////////////
              //Copy original file to new directory
              if (!copy($userViewPath, $newUserViewPath)) {
                  echo "failed to copy $userViewPath...<br>";
              }else{
                echo "Success to copy $userViewPath...<br>";
                fclose($isCreateUserViewFile);
                unlink($userViewPath);
              }

              //Copy original file to new directory
              if (!copy($userListPath, $newUserListPath)) {
                  echo "failed to copy $userListPath...<br>";
              }else{
                echo "Success to copy $userListPath...<br>";
                fclose($isCreateUserListFile);
                unlink($userListPath);
              }
            }
            
          }//End loop models
        }//End if validate "models" node
      }//End loop mvc
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