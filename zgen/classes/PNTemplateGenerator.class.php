<?php
  class PNTemplateGenerator{

    var $module;
    var $mindmap;

    function __construct($module, $mindmap) {
        $this->module = $module;
        $this->mindmap = $mindmap;
    }

    function createPNTemplateFile() {

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

              //Get code data for method

              $className = ucfirst($table->attributes()->TEXT);
              $moduleName = $this->module;
              $adminForm = $this->createPNTemplateCode($className, $moduleName, $table->node);

              //Write the pntemplate admin code to file
              fwrite($isCreateFormFile, $adminForm);
              fwrite($isCreateViewFile, $adminView);
              fwrite($isCreateListFile, $adminList);

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


    function createPNTemplateCode($className, $moduleName ,$fieldArray){

      $code .= "<fieldset>"."\r\n";
      $code .= "  <legend>".$className."</legend>"."\r\n";
      $code .= "    <form id=\"form\" class=\"form\" action=\"<!--[pnmodurl modname='".$moduleName."' type='adminform' func='submit' ctrl='".$className."']-->\" method=\"post\" >"."\r\n";
      $code .= "        <input type=\"hidden\" name=\"form[id]\" value=\"<!--[\$smarty.get.id]-->\" />"."\r\n";
      $code .= "        <TABLE width=\"100%\" border=\"0\">"."\r\n";
          //Loop get all field
          foreach($fieldArray as $field){
            $fieldName = $field->attributes()->TEXT;
            $value = explode(":", $fieldName);
            $fieldnameValue = $value[0];

            //Check field is join
            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="zkextend") && (strtolower($fieldnameValue)!="id") && (strpos($fieldnameValue, "id") <= 0)){
              $code .= "              <TR>"."\r\n";
              $code .= "                  <TD align=\"right\" width=\"20%\"><B>".$fieldnameValue." : </B></TD>"."\r\n";
              $code .= "                  <TD align=\"left\" width=\"80%\">"."\r\n";
              $code .= "                    <input id=\"name\" type=\"text\" name=\"form[".$fieldnameValue."]\" value=\"<!--[\$form.".$fieldnameValue."]-->\" title=\"".$fieldnameValue."\" class=\"required\"  />"."\r\n";
              $code .= "                  </TD>"."\r\n";
              $code .= "              </TR>"."\r\n";
            }
          }

      $code .= "             <TR>"."\r\n";
      $code .= "                <TD align=\"left\" width=\"100%\">"."\r\n";
      $code .= "                  <INPUT TYPE=\"submit\" value=\"submit\">"."\r\n";
      $code .= "                  <input type=\"button\" name=\"Cancel\" value=\"Cancel\" onclick=\"window.location = '<!--[pnmodurl modname=".$moduleName." type=admin func=list ctrl=".$className."]-->'\" />"."\r\n";
      $code .= "                </TD>"."\r\n";
      $code .= "            </TR>"."\r\n";
      $code .= "        </TABLE>"."\r\n";
      $code .= "    </form><BR>"."\r\n";
      $code .= "</fieldset>"."\r\n";

      return $code;
    }
  }
?>