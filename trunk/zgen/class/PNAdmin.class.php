<?php

  class PNAdmin {

    var $table;
    var $module;
    var $filePath;
    var $mindmap;
    var $newFilePath;

    function __construct($module){
      $this->module = $module;
      $this->mindmap = $mindmap;
      $this->filePath = "pnadmin.php";
      $this->newFilePath = $module."/"."pnadmin.php";
    }

    function createPNAdminFile(){
      $isCreateFile = FileUtil::createFile($this->filePath);
      if($isCreateFile){
        echo $this->filePath." Created.<br>";
        $text .= $this->createHeaderCode();
        $text .= $this->createPermissionMethodCode();
        $text .= $this->createRenderMethodCode();
        $text .= $this->createMainMethodCode();
        $text .= $this->createPageMethodCode();
        $text .= $this->createViewMethodCode();
        $text .= $this->createFormMethodCode();
        $text .= $this->createListMethodCode();
        $text .= $this->createDeleteMethodCode();
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

    function createHeaderCode(){
        $code = "<?php";
        $code .= "\r\n";
        return $code;
    }


    function createPermissionMethodCode(){
        //////////////////////////////////
        //Start permission method
        /////////////////////////////////
        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* //////////////////////////////////////////////////";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* auto execute , for initialize config variable";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* this function auto call every page has been fetch";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_permission() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  // Security check";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (!SecurityUtil::checkPermission('".$this->module."::', '::', ACCESS_ADMIN)) {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }

  function createRenderMethodCode(){
        //////////////////////////////////
        //Start _preRender method
        /////////////////////////////////
        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* smarty template auto call before render";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function _preRender(&\$render){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$lang    = FormUtil::getPassedValue ('lang', false , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render->assign ('_GET', \$_GET);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render->assign ('_POST', \$_POST);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render->assign ('_REQUEST', \$_REQUEST);";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render->assign('ctrl', \$ctrl);";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (\$lang){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign('lang', \$lang);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }else{";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign('lang', pnUserGetLang());";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render->assign('access_edit', true);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }

  function createMainMethodCode(){
        //////////////////////////////////
        //Start _admin_main method
        /////////////////////////////////
        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* Main user function, simply return the index page.";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* @author Parinya Bumrungchoo";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* @return string HTML string";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_admin_main() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  ".$this->module."_permission();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  return ".$this->module."_admin_list();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }

    function createPageMethodCode(){
        //////////////////////////////////
        //Start _admin_page method
        /////////////////////////////////
        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* display page with out class loader";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_admin_page() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  ".$this->module."_permission();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$ctrl the class name";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$method the method of request for edit or view enum[ view | form | delete | list | page]";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$func  = FormUtil::getPassedValue ('func', 'page' , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render = pnRender::getInstance('".$this->module."');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  _preRender(\$render);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  return \$render->fetch('admin_'.\$func.'_'.strtolower(\$ctrl).'.htm');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }

    function createViewMethodCode(){
        //////////////////////////////////
        //Start _admin_page method
        /////////////////////////////////
        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* display page with class that extend Object ";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_admin_view() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  ".$this->module."_permission();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$ctrl the class name";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$method the method of request for edit or view enum[ view | form | delete | list | page]";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$func  = FormUtil::getPassedValue ('func', 'view' , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$lang enum[eng | jpn | tha]";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$lang    = FormUtil::getPassedValue ('lang', null , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$id the id no if edit form";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$id      = FormUtil::getPassedValue ('id', null , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //pagnigation variable";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$filter  = FormUtil::getPassedValue ('filter', 0);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$offset  = FormUtil::getPassedValue ('startnum', 0);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$sort    = FormUtil::getPassedValue ('sort', '');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$where   = '';";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$pagesize = pnModGetVar ('".$this->module."', 'pagesize') ? pnModGetVar ('".$this->module."', 'pagesize') : 100;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render = pnRender::getInstance('".$this->module."');";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (\$id){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    //load class";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (!(\$class = Loader::loadClassFromModule ('".$this->module."', \$ctrl, false)))";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      return LogUtil::registerError ('Unable to load class [\$ctrl] ...');";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$object  = new \$class ();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$object->get(\$id);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (method_exists(\$object,'selectExtendResult')){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$resultex = \$object->selectExtendResult();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$render->assign('extendResult', \$resultex);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign ('view', \$object->_objData);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  return \$render->fetch('admin_'.\$func.'_'.strtolower(\$ctrl).'.htm');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }


    function createFormMethodCode(){

        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* display page with class that extend Object Array";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_admin_form() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  ".$this->module."_permission();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$ctrl the class name";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
         $code .= "  //\$method the method of request for edit or view enum[ view | form | delete | list | page]";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$func  = FormUtil::getPassedValue ('func', 'form' , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$id the id no if edit form";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$id      = FormUtil::getPassedValue ('id', null , 'GET');";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //pagnigation variable";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$filter  = FormUtil::getPassedValue ('filter', 0);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$offset  = FormUtil::getPassedValue ('startnum', 0);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$sort    = FormUtil::getPassedValue ('sort', '');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$where   = '';";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$step    = FormUtil::getPassedValue ('step', null , 'GET');";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$pagesize = pnModGetVar ('".$this->module."', 'pagesize') ? pnModGetVar ('".$this->module."', 'pagesize') : 100;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render = pnRender::getInstance('".$this->module."');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$mode = null;";
        $code .= "\r\n";
        $code .= "  ";

        $code .= "  //load class";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (!(\$class = Loader::loadClassFromModule ('".$this->module."', \$ctrl, false)))";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    return LogUtil::registerError ('Unable to load class [\$ctrl] ...');";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "  ";

        $code .= "  \$object  = new \$class ();";
        $code .= "\r\n";
        $code .= "  ";

        $code .= "  if (\$id && \$object){";
        $code .= "\r\n";
        $code .= "  ";

        $code .= "    \$object->get(\$id);";
        $code .= "\r\n";
        $code .= "  ";

        $code .= "    \$mode = 'edit';";
        $code .= "\r\n";
        $code .= "  ";  
        $code .= "    \$render->assign ('form', \$object->_objData);";
        $code .= "\r\n";
        $code .= "  ";
      
        $code .= "  }else{";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$mode = 'new';";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render->assign ('mode', \$mode);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (method_exists(\$object,'selectExtendResult')){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$resultex = \$object->selectExtendResult();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign('extendResult', \$resultex);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  _preRender(\$render);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    return \$render->fetch('admin_'.\$func.'_'.strtolower($ctrl).'.htm');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }




    function createListMethodCode(){

        $code .= "  ";
        $code .= "/**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "* display page with class that extend Object Array";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "*/";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_admin_list() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  ".$this->module."_permission();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$ctrl the class name";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$ctrl    = FormUtil::getPassedValue ('ctrl', 'Province' , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //\$method the method of request for edit or view enum[ view | form | delete | list | page]";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$func  = FormUtil::getPassedValue ('func', 'list' , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$is_export = false;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$pagesize = pnModGetVar ('".$this->module."', 'pagesize') ? pnModGetVar ('".$this->module."', 'pagesize') : 100;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$render = pnRender::getInstance('".$this->module."');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  //check is export";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$export = FormUtil::getPassedValue ('export', false);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$button_export = FormUtil::getPassedValue ('button_export', false);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$button_export_x = FormUtil::getPassedValue ('button_export_x', false);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (\$export || \$button_export || \$button_export_x){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$is_export = true;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$class = Loader::loadClassFromModule ('".$this->module."', $ctrl, true);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (\$class){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$objectArray = new \$class ();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$where   = null;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$sort = null;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (method_exists(\$objectArray,'genFilter')){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$where = \$objectArray->genFilter();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (method_exists(\$objectArray,'genSort')){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "        $sort = \$objectArray->genSort();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (method_exists(\$objectArray,'selectExtendResult')){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "        \$resultex = \$objectArray->selectExtendResult();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "        \$render->assign('extendResult', \$resultex);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (empty(\$where)){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "        \$where = \$objectArray->_objWhere;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (empty(\$sort)){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$sort = \$objectArray->_objSort;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    //pagnigation variable";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$filter  = FormUtil::getPassedValue ('filter', 0);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$offset  = FormUtil::getPassedValue ('startnum', 0);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    //\$sort    = FormUtil::getPassedValue ('sort', $sort);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    //Split page";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$pagesize = 100;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$pager = array();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$pager['numitems']     = \$objectArray->getCount (\$where , true);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$pager['itemsperpage'] = \$pagesize;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign ('startnum', \$offset);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign ('pager', \$pager);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$objectArray->get (\$where, \$sort , \$offset, \$pagesize);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    //assign to view";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$render->assign('objectArray', \$objectArray->_objData);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  return \$render->fetch('admin_'.\$func.'_'.strtolower(\$ctrl).'.htm');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";

        return $code;
    }

    function createDeleteMethodCode(){
        $code .= "      /**";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      * for delete object for database by specify id";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      */";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "function ".$this->module."_admin_delete() {";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  ".$this->module."_permission();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$id      = FormUtil::getPassedValue ('id', null , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  \$forward = FormUtil::getPassedValue ('forward', null , 'GET');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  if (\$id){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    //load class";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (!(\$class = Loader::loadClassFromModule ('".$this->module."', \$ctrl, false)))";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      return LogUtil::registerError ('Unable to load class [\$ctrl] ...');";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$object  = new \$class ();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$object->_objData[\$object->_objField] = \$id;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    \$object->delete ();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if(\$forward){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$list_url = pnModURL('".$this->module."', 'admin', 'list' , array('ctrl'   => \$ctrl);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "                                                              )";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "                          );";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }else{";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$list_url = pnModURL('".$this->module."', 'admin', 'list' , array('ctrl'=>\$ctrl";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "                                                              )";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    );";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    if (method_exists(\$object,'genForward')){";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      \$forwar_url = \$object->genForward();";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      pnRedirect(\$forwar_url);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }else{";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "      pnRedirect(\$list_url);";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "    die;";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "  }";
        $code .= "\r\n";
        $code .= "  ";
        $code .= "}";
        $code .= "\r\n";
        $code .= "\r\n";
        return $code;
    }


    function createFooterCode(){

    }
  }

?>