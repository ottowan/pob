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

              //Get code data for method & Write the pntemplate admin code to file
              $className = ucfirst($table->attributes()->TEXT);
              $moduleName = $this->module;
              $adminForm = $this->createPNTemplateAdminForm($className, $moduleName, $table->node);
              $adminView = $this->createPNTemplateAdminView($className, $moduleName, $table->node);
              $adminList = $this->createPNTemplateAdminList($className, $moduleName, $table->node);
              fwrite($isCreateFormFile, $adminForm);
              fwrite($isCreateViewFile, $adminView);
              fwrite($isCreateListFile, $adminList);

              //Get code data for method & Write the pntemplate user code to file
              $userView = $this->createPNTemplateUserView($className, $moduleName, $table->node);
              $userList = $this->createPNTemplateUserList($className, $moduleName, $table->node);
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

    function createPNTemplateAdminForm($className, $moduleName , $fieldArray){

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
            if((strtolower($fieldnameValue)!="zkjoin") && strtolower($fieldnameValue)!="zkextend" && (strtolower($fieldnameValue)!="id") ){
              if(strpos($fieldnameValue, "_id") > 0){

                $fieldLabel = explode("_id", $fieldnameValue);
                $fieldnameValueResult = $fieldLabel[0];
                $extendTable = strtolower($fieldnameValueResult);
                $extendClass = ucfirst(strtolower($extendTable));

                  $code .= "              <TR>"."\r\n";
                  $code .= "                <TD align=\"right\" width=\"20%\" valign=\"top\" ><B>".$extendTable." : </B></TD>"."\r\n";
                  $code .= "                    <TD align=\"left\" width=\"80%\">"."\r\n";
                  $code .= "                    <!--[selector_object_array_ex modname='".$moduleName."'"."\r\n";
                  $code .= "                                                  class='".$extendClass."'"."\r\n";
                  $code .= "                                                  field='id'"."\r\n";
                  $code .= "                                                  displayField='name'"."\r\n";
                  $code .= "                                                  name='form[".$fieldnameValue."]'"."\r\n"; 
                  $code .= "                                                  selectedValue=\$form.id"."\r\n";
                  $code .= "                                                  sort='".$extendTable."_name'"."\r\n";
                  $code .= "                     ]-->"."\r\n";
                  $code .= "                    </TD>"."\r\n";
                  $code .= "              </TR>"."\r\n";
/*
                //loop table
                foreach($field->node as $table){
                  $code .= "              <TR>"."\r\n";
                  $code .= "                <TD align=\"right\" width=\"20%\" valign=\"top\" ><B>".$table->attributes()->TEXT." : </B></TD>"."\r\n";
                  $code .= "                    <TD align=\"left\" width=\"80%\">"."\r\n";

                  $code .= "                    </TD>"."\r\n";
                  $code .= "              </TR>"."\r\n";

                }*/
              }else{
                $code .= "              <TR>"."\r\n";
                $code .= "                  <TD align=\"right\" width=\"20%\"><B>".$fieldnameValue." : </B></TD>"."\r\n";
                $code .= "                  <TD align=\"left\" width=\"80%\">"."\r\n";
                $code .= "                    <input id=\"name\" type=\"text\" name=\"form[".$fieldnameValue."]\" value=\"<!--[\$form.".$fieldnameValue."]-->\" title=\"".$fieldnameValue."\" class=\"required\"  />"."\r\n";
                $code .= "                  </TD>"."\r\n";
                $code .= "              </TR>"."\r\n";
              }
            }

          }

      $code .= "             <TR>"."\r\n";
      $code .= "                <TD align=\"left\" width=\"100%\" colspan=\"2\">"."\r\n";
      $code .= "                  <INPUT TYPE=\"submit\" value=\"submit\">"."\r\n";
      $code .= "                  <input type=\"button\" name=\"Cancel\" value=\"Cancel\" onclick=\"window.location = '<!--[pnmodurl modname=".$moduleName." type=admin func=list ctrl=".$className."]-->'\" />"."\r\n";
      $code .= "                </TD>"."\r\n";
      $code .= "            </TR>"."\r\n";
      $code .= "        </TABLE>"."\r\n";
      $code .= "    </form><BR>"."\r\n";
      $code .= "</fieldset>"."\r\n";

      return $code;
    }


    function createPNTemplateAdminView($className, $moduleName ,$fieldArray){

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
            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="id") && (strpos($fieldnameValue, "id") <= 0)){
              if(strtolower($fieldnameValue)=="zkextend"){

                //loop table
                foreach($field->node as $table){
                  $code .= "              <TR>"."\r\n";
                  $code .= "                <TD align=\"right\" width=\"20%\" valign=\"top\" ><B>".$table->attributes()->TEXT." : </B></TD>"."\r\n";
                  $code .= "                    <TD align=\"left\" width=\"80%\">"."\r\n";
                  $code .= "                  <!--[foreach from=\$extendResult.".$table->attributes()->TEXT." item=item]-->"."\r\n";

                  //loop field
                  foreach($table->node as $field){
                    $code .= "                        <!--[\$item.".$field->attributes()->TEXT."]-->"."\r\n";
                  }
                  $code .= "                        <BR>"."\r\n";
                  $code .= "                  <!--[/foreach]-->"."\r\n";
                  $code .= "                    </TD>"."\r\n";
                  $code .= "              </TR>"."\r\n";
                }
              }else{
                $code .= "              <TR>"."\r\n";
                $code .= "                  <TD align=\"right\" width=\"20%\" valign=\"top\" ><B>".$fieldnameValue." : </B></TD>"."\r\n";
                $code .= "                  <TD align=\"left\" width=\"80%\">"."\r\n";
                $code .= "                    <!--[\$view.".$fieldnameValue."]-->\r\n";
                $code .= "                  </TD>"."\r\n";
                $code .= "              </TR>"."\r\n";
              }
            }
          }
      $code .= "        </TABLE>"."\r\n";
      $code .= "    </form><BR>"."\r\n";
      $code .= "</fieldset>"."\r\n";

      return $code;
    }

    function createPNTemplateUserView($className, $moduleName ,$fieldArray){

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
            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="id") && (strpos($fieldnameValue, "id") <= 0)){
              if(strtolower($fieldnameValue)=="zkextend"){

                //loop table
                foreach($field->node as $table){
                  $code .= "              <TR>"."\r\n";
                  $code .= "                <TD align=\"right\" width=\"20%\" valign=\"top\" ><B>".$table->attributes()->TEXT." : </B></TD>"."\r\n";
                  $code .= "                    <TD align=\"left\" width=\"80%\">"."\r\n";
                  $code .= "                  <!--[foreach from=\$extendResult.".$table->attributes()->TEXT." item=item]-->"."\r\n";

                  //loop field
                  foreach($table->node as $field){
                    $code .= "                        <!--[\$item.".$field->attributes()->TEXT."]-->"."\r\n";
                  }
                  $code .= "                        <BR>"."\r\n";
                  $code .= "                  <!--[/foreach]-->"."\r\n";
                  $code .= "                    </TD>"."\r\n";
                  $code .= "              </TR>"."\r\n";
                }
              }else{
                $code .= "              <TR>"."\r\n";
                $code .= "                  <TD align=\"right\" width=\"20%\" valign=\"top\" ><B>".$fieldnameValue." : </B></TD>"."\r\n";
                $code .= "                  <TD align=\"left\" width=\"80%\">"."\r\n";
                $code .= "                    <!--[\$view.".$fieldnameValue."]-->\r\n";
                $code .= "                  </TD>"."\r\n";
                $code .= "              </TR>"."\r\n";
              }
            }
          }
      $code .= "        </TABLE>"."\r\n";
      $code .= "    </form><BR>"."\r\n";
      $code .= "</fieldset>"."\r\n";

      return $code;
    }

    function createPNTemplateAdminList($className, $moduleName ,$fieldArray){

          $code .= "<fieldset>"."\r\n";
          $code .="  <legend>&nbsp;".$className."&nbsp;</legend>"."\r\n";
          $code .="  <!--[yppager show=page "."\r\n";
          $code .="                   img_prev=\"modules/".$moduleName."/pnimages/back.png\""."\r\n";
          $code .="                   img_next=\"modules/".$moduleName."/pnimages/next.png\""."\r\n";
          $code .="                   rowcount=\$pager.numitems "."\r\n";
          $code .="                   limit=\$pager.itemsperpage "."\r\n";
          $code .="                   posvar=startnum "."\r\n";
          $code .="                   shift=0 "."\r\n";
          $code .="  ]-->"."\r\n";
          $code .="  <TABLE width=\"100%\">"."\r\n";
          $code .="    <TR bgcolor=\"#99CCCC\">"."\r\n";

          foreach($fieldArray as $key=>$field){
            $fieldName = $field->attributes()->TEXT;
            $value = explode(":", $fieldName);
            $fieldnameValue = $value[0];

            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="zkextend")){
              $code .="      <TH>".$fieldnameValue."</TH>"."\r\n";
            }
          }

          $code .="      <TH>&nbsp;</TH>"."\r\n";
          $code .= ""."\r\n";
          $code .="  </TR>"."\r\n";
          $code .="    <!--[foreach from=\$objectArray item=item]-->"."\r\n";
          $code .="      <TR bgcolor=\"<!--[cycle values='#FFFFFF,#E8EFF7']-->\">"."\r\n";

          foreach($fieldArray as $key=>$field){
            $fieldName = $field->attributes()->TEXT;
            $value = explode(":", $fieldName);
            $fieldnameValue = $value[0];
            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="zkextend")){
              $code .="        <TD><!--[\$item.".$fieldnameValue."]--></TD>"."\r\n";
            }
          }

          $code .="        <TD>"."\r\n";
          $code .="          <a target=\"_blank\" href=\"<!--[pnmodurl modname='".$moduleName."' type='admin' func='form' ctrl='".$className."' id=\$item.id]-->\">"."\r\n";
          $code .="            <img src=\"modules/".$moduleName."/pnimages/pencil.png\" />"."\r\n";
          $code .="          </a>"."\r\n";
          $code .="          <a href=\"<!--[pnmodurl modname='".$moduleName."' type='admin' func='delete' ctrl='".$className."' id=\$item.id ]-->\" "."\r\n";
          $code .="             onclick=\"return confirm('Are you sure you want to delete?')\""."\r\n";
          $code .="          >"."\r\n";
          $code .="            <img src=\"modules/".$moduleName."/pnimages/delete.png\" />"."\r\n";
          $code .="          </a>"."\r\n";
          $code .="        </TD>"."\r\n";
          $code .= "      </TR>"."\r\n";
          $code .="    <!--[/foreach]-->"."\r\n";
          $code .="  </TABLE>"."\r\n";
          $code .="  <!--[yppager show=page "."\r\n";
          $code .="                   img_prev=\"modules/".$moduleName."/pnimages/back.png\""."\r\n";
          $code .="                   img_next=\"modules/".$moduleName."/pnimages/next.png\""."\r\n";
          $code .="                   rowcount=\$pager.numitems "."\r\n";
          $code .="                   limit\=$pager.itemsperpage "."\r\n";
          $code .="                   posvar=startnum "."\r\n";
          $code .="                   shift=0 "."\r\n";
          $code .="  ]-->"."\r\n";
          $code .="</fieldset>"."\r\n";

      return $code;
    }


    function createPNTemplateUserList($className, $moduleName ,$fieldArray){

          $code .= "<fieldset>"."\r\n";
          $code .="  <legend>&nbsp;".$className."&nbsp;</legend>"."\r\n";
          $code .="  <!--[yppager show=page "."\r\n";
          $code .="                   img_prev=\"modules/".$moduleName."/pnimages/back.png\""."\r\n";
          $code .="                   img_next=\"modules/".$moduleName."/pnimages/next.png\""."\r\n";
          $code .="                   rowcount=\$pager.numitems "."\r\n";
          $code .="                   limit=\$pager.itemsperpage "."\r\n";
          $code .="                   posvar=startnum "."\r\n";
          $code .="                   shift=0 "."\r\n";
          $code .="  ]-->"."\r\n";
          $code .="  <TABLE width=\"100%\">"."\r\n";
          $code .="    <TR bgcolor=\"#99CCCC\">"."\r\n";

          foreach($fieldArray as $key=>$field){
            $fieldName = $field->attributes()->TEXT;
            $value = explode(":", $fieldName);
            $fieldnameValue = $value[0];

            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="zkextend")){
              $code .="      <TH>".$fieldnameValue."</TH>"."\r\n";
            }
          }

          $code .= ""."\r\n";
          $code .="  </TR>"."\r\n";
          $code .="    <!--[foreach from=\$objectArray item=item]-->"."\r\n";
          $code .="      <TR bgcolor=\"<!--[cycle values='#FFFFFF,#E8EFF7']-->\">"."\r\n";

          foreach($fieldArray as $key=>$field){
            $fieldName = $field->attributes()->TEXT;
            $value = explode(":", $fieldName);
            $fieldnameValue = $value[0];
            if((strtolower($fieldnameValue)!="zkjoin") && (strtolower($fieldnameValue)!="zkextend")){
              $code .="        <TD><!--[\$item.".$fieldnameValue."]--></TD>"."\r\n";
            }
          }

          $code .="    <!--[/foreach]-->"."\r\n";
          $code .="  </TABLE>"."\r\n";
          $code .="  <!--[yppager show=page "."\r\n";
          $code .="                   img_prev=\"modules/".$moduleName."/pnimages/back.png\""."\r\n";
          $code .="                   img_next=\"modules/".$moduleName."/pnimages/next.png\""."\r\n";
          $code .="                   rowcount=\$pager.numitems "."\r\n";
          $code .="                   limit\=$pager.itemsperpage "."\r\n";
          $code .="                   posvar=startnum "."\r\n";
          $code .="                   shift=0 "."\r\n";
          $code .="  ]-->"."\r\n";
          $code .="</fieldset>"."\r\n";

      return $code;
    }






  }
?>