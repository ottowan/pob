<?php

class PNUser{

  var $table;
  var $module;
  var $file;
  var $mindmap;
  function __construct($module, $mindmap) {
      $this->module = $module;
      $this->mindmap = $mindmap;
      $this->filePath = "pnadminform.php";
      $this->newFilePath = $module."/"."pnadminform.php";
  }

  function createPNAdminFormFile() {
      $isCreateFile = FileUtil::createFile($this->filePath);
      if($isCreateFile){
        echo $this->filePath." Created.<br>";
        $text .= $this->createHeaderCode();
        $text .= $this->createMainMethodCode();
        $text .= $this->createPermissionMethodCode();
        $text .= $this->createSubmitMethodCode();
        $text .= $this->createGenerateURLMethodCode();
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


  function createHeaderCode() {
      $code .= "<?php";
      $code .= "\r\n";
      return $code;
  }

  function createPermissionMethodCode() {
    $code .= "  function ".$this->module."_permission() {";$code .= "\r\n";
    $code .= "    // Security check";$code .= "\r\n";
    $code .= "    //we are allow for admin access level , see in config.php variable name ACCESS_EDIT";$code .= "\r\n";
    $code .= "    if (!SecurityUtil::checkPermission('".$this->module."::', '::', ACCESS_EDIT)) {";$code .= "\r\n";
    $code .= "        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createMainMethodCode() {
    $code .= "  function ".$this->module."_user_main (){";$code .= "\r\n";
    $code .= "    ".$this->module."_permission();";$code .= "\r\n";
    $code .= "    ".$this->module."_user_page();";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createPNRenderMethodCode() {

    $code .= "  /**";$code .= "\r\n";
    $code .= "  * smarty template auto call before render";$code .= "\r\n";
    $code .= "  */";$code .= "\r\n";
    $code .= "  function _preRender(&\$render){";$code .= "\r\n";
    $code .= "    \$lang    = FormUtil::getPassedValue ('lang', false , 'GET');";$code .= "\r\n";
    $code .= "    \$ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$render->assign ('_GET', \$_GET);";$code .= "\r\n";
    $code .= "    \$render->assign ('_POST', \$_POST);";$code .= "\r\n";
    $code .= "    \$render->assign ('_REQUEST', \$_REQUEST);";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    \$render->assign('ctrl', \$ctrl);";$code .= "\r\n";
    $code .= "    \$render->assign('user',   InnoUtil::getUserInfo());";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "    if (\$lang){";$code .= "\r\n";
    $code .= "      \$render->assign('lang', \$lang);";$code .= "\r\n";
    $code .= "    }else{";$code .= "\r\n";
    $code .= "      \$render->assign('lang', pnUserGetLang());";$code .= "\r\n";
    $code .= "    }";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createFooterCode() {
    $code .= "  /**";$code .= "\r\n";
    $code .= "  * display page with out class loader";$code .= "\r\n";
    $code .= "  */";$code .= "\r\n";
    $code .= "  function ".$this->module."_user_page() {";$code .= "\r\n";
    $code .= "      //\$ctrl the class name";$code .= "\r\n";
    $code .= "      \$ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');";$code .= "\r\n";
    $code .= "      //\$method the method of request for edit or view enum[ view | form | delete | list | page]";$code .= "\r\n";
    $code .= "      \$func  = FormUtil::getPassedValue ('func', 'page' , 'GET');";$code .= "\r\n";
    $code .= "      \$render = pnRender::getInstance('".$this->module."');";$code .= "\r\n";
    $code .= "      ";$code .= "\r\n";
    $code .= "      _preRender(\$render);";$code .= "\r\n";
    $code .= "      //try to load class";$code .= "\r\n";
    $code .= "      \$class = Loader::loadClassFromModule ('".$this->module."','User' . \$ctrl, false);";$code .= "\r\n";
    $code .= "      if ($class){";$code .= "\r\n";
    $code .= "        \$object  = new $class ();";$code .= "\r\n";
    $code .= "        if (method_exists(\$object,'selectExtendResult')){";$code .= "\r\n";
    $code .= "          \$resultex = \$object->selectExtendResult();";$code .= "\r\n";
    $code .= "          \$render->assign('extendResult', \$resultex);";$code .= "\r\n";
    $code .= "        }";$code .= "\r\n";
    $code .= "      }";$code .= "\r\n";
    $code .= "";$code .= "\r\n";
    $code .= "      return $render->fetch('user_'.\$func.'_'.strtolower(\$ctrl).'.htm');";$code .= "\r\n";
    $code .= "  }";$code .= "\r\n";$code .= "\r\n";

    return $code;
  }

  function createFooterCode() {
    $code .="?>";
    return $code;
  }
}

?>


<?php




/**
* display page with class that extend Object 
*/
function VoteDataCenter_user_view() {
    //_autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'view' , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('VoteDataCenter', 'pagesize') ? pnModGetVar ('VoteDataCenter', 'pagesize') : 10;
    $render = pnRender::getInstance('VoteDataCenter');
    
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('VoteDataCenter',$ctrl, false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->get($id);
      if (method_exists($object,'genFilter')){
        $where = $object->genFilter();
      }
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      $render->assign ('view', $object->_objData);
    }
    _preRender($render);
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}

/**
* display page with class that extend Object Array
*/
function VoteDataCenter_user_list() {
    VoteDataCenter_user_permission();
    //_autoexecute();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Layer' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');

    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $pagesize = pnModGetVar ('VoteDataCenter', 'pagesize') ? pnModGetVar ('VoteDataCenter', 'pagesize') : 100;
       
    $render = pnRender::getInstance('VoteDataCenter');

    if (!($class = Loader::loadClassFromModule ('VoteDataCenter',$ctrl, true)))
      return LogUtil::registerError ("Unable to load class [$ctrl] ...");

    $objectArray = new $class ();
    $where   = null;
    $sort = null;
    if (method_exists($objectArray,'genFilter')){
      $where = $objectArray->genFilter();
    }
    if (method_exists($objectArray,'genSort')){
      $sort = $objectArray->genSort();
    }
    if (method_exists($objectArray,'selectExtendResult')){
      $resultex = $objectArray->selectExtendResult();
      $render->assign('extendResult', $resultex);
    }
    if (empty($where)){
      $where = implode(' AND ',$objectArray->_objWhere);
    }else if (is_array($objectArray->_objWhere)){
      $where .= implode(' AND ',$objectArray->_objWhere);
    }else if (!empty($objectArray->_objWhere)){
      $where .= ' AND ' . $objectArray->_objWhere;
    }
    if (empty($sort)){
      $sort = $objectArray->_objSort;
    }


    //Spilt page
    $pager = array();
    $pager['numitems']     = $objectArray->getCount($where , true);
    $pager['itemsperpage'] = $pagesize;
    $render->assign ('startnum', $offset);
    $render->assign ('pager', $pager);
    $objectArray->get ($where, $sort , $offset, $pagesize);

    //assign to view
    $render->assign('objectArray', $objectArray->_objData);

    _preRender($render);

    //var_dump($render);
    //exit;
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
    pnShutDown();
}

function VoteDataCenter_user_form() {
    VoteDataCenter_user_permission();
    //$uid = FormUtil::getPassedValue ('uid', false , 'GET'); //icon | image | video | model
     //$ctrl the class name
      $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
      //$method the method of request for edit or view enum[ view | form | delete | list | page]
      $func  = FormUtil::getPassedValue ('func', 'form' , 'GET');
      //$id the id no if edit form
      $id      = FormUtil::getPassedValue ('id', null , 'GET');
      //pagnigation variable
      $filter  = FormUtil::getPassedValue ('filter', 0);
      $offset  = FormUtil::getPassedValue ('startnum', 0);
      $sort    = FormUtil::getPassedValue ('sort', '');
      $where   = '';

      $pagesize = pnModGetVar ('VoteDataCenter', 'pagesize') ? pnModGetVar ('VoteDataCenter', 'pagesize') : 100;
      $render = pnRender::getInstance('VoteDataCenter');
      $mode = null;

      //load class
      if (!($class = Loader::loadClassFromModule ('VoteDataCenter',ucfirst($ctrl), false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();


      if ($id && $object){

        $object->get($id);

        $mode = 'edit';  
        $render->assign ('form', $object->_objData);
        //var_dump($object->_objData);
        //exit;
      }else{
        $mode = 'new';
      }
      $render->assign ('mode', $mode);
      
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      _preRender($render);
      return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}

/**
* for delete object for database by specify id
*/
function VoteDataCenter_user_delete() {
    VoteDataCenter_user_permission();
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', false , 'GET');
    $forward = FormUtil::getPassedValue ('forward', false , 'GET');

    $uid = FormUtil::getPassedValue ('uid', false , 'GET'); //icon | image | video | model
    $user    = InnoUtil::getUserInfo();

    if ( $id && ((($uid == $user['uid']) && ($uid != '') && $uid ) || (intval($user['level']) == intval(constant('ACCESS_EDIT')) )) ) {
      //load class
      if (!($class = Loader::loadClassFromModule ('VoteDataCenter', ucfirst($ctrl), false)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");

      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();
      
      if (method_exists($object,'genPermission')){
        $permit = $object->genPermission();
        if (empty($permit)){
          return LogUtil::registerError ("ส่วนนี้ถูกจำกัดให้ใช้ได้เฉพาะสมาชิกเท่านั้น");
        }
      }
      if (method_exists($object,'genForward')){
        $forward_url = $object->genForward();
      }else if (!is_array($forward) && $forward){
        $forward_url =  str_replace("&amp;", "&", $forward);
      }else if (is_array($forward)){
        $forward_url = InnoUtil::createUrlFromArray($forward);
      }else{
        $forward_url = pnModURL('VoteDataCenter', 'user', 'list' , array('ctrl'=>$ctrl));
      }
      pnRedirect($forward_url);
      die;
    }
    else{
     $url = pnModURL('Users', 'user', 'loginscreen' , array('ctrl'=>$ctrl) );

      pnRedirect($url);
      die;
    }
}



