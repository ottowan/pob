<?php
  /**
  * //////////////////////////////////////////////////
  * auto execute , for initialize config variable
  * this function auto call every page has been fetch
  */
  function POBMember_permission() {
    // Security check
    //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN
    if (!SecurityUtil::checkPermission('POBMember::', '::', ACCESS_ADMIN)) {
        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
    }
  }

  /**
  * smarty template auto call before render
  */
  function _languageRender(&$render){

    Loader::loadClass('LanguageUtil');
    $languages = LanguageUtil::getLanguages();
    $currentLanguage = pnUserGetLang();
    $render->assign('languages', $languages);
    $render->assign('currentLanguage', $currentLanguage);
    
  }

  /**
  * Main user function, simply return the index page.
  * @author Parinya Bumrungchoo
  * @return string HTML string
  */
  function POBMember_admin_main() {
    POBMember_permission();
    return POBMember_admin_list();
  }

  /**
  * display page with out class loader
  */
  function POBMember_admin_page() {
    POBMember_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('POBMember');
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object 
  */
  function POBMember_admin_view() {
    POBMember_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'view' , 'GET');
    //$lang enum[eng | jpn | tha]
    $lang    = FormUtil::getPassedValue ('lang', null , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';

    $pagesize = pnModGetVar ('POBMember', 'pagesize') ? pnModGetVar ('POBMember', 'pagesize') : 100;
    $render = pnRender::getInstance('POBMember');

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBMember', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');

      $object  = new $class ();
      $object->get($id);
      if (method_exists($object,'selectExtendResult')){
        $resultex = $object->selectExtendResult();
        $render->assign('extendResult', $resultex);
      }
      $render->assign ('view', $object->_objData);
    }
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object Array
  */
  function POBMember_admin_form() {
    POBMember_permission();
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
    $step    = FormUtil::getPassedValue ('step', null , 'GET');

    $pagesize = pnModGetVar ('POBMember', 'pagesize') ? pnModGetVar ('POBMember', 'pagesize') : 100;
    $render = pnRender::getInstance('POBMember');
    $mode = null;
    //load class
    if (!($class = Loader::loadClassFromModule ('POBMember', $ctrl, false)))
      return LogUtil::registerError ('Unable to load class [$ctrl] ...');

    $object  = new $class ();
    if ($id && $object){
      $object->get($id);
      $mode = 'edit';
      $render->assign ('form', $object->_objData);
    }else{
      $mode = 'new';
    }
    $render->assign ('mode', $mode);
    if (method_exists($object,'selectExtendResult')){
      $resultex = $object->selectExtendResult();
      $render->assign('extendResult', $resultex);
    }
    _languageRender($render);
      return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

  /**
  * display page with class that extend Object Array
  */
  function POBMember_admin_list() {
    POBMember_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Member' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');
  
    $is_export = false;
  
    $pagesize = pnModGetVar ('POBMember', 'pagesize') ? pnModGetVar ('POBMember', 'pagesize') : 100;
    $render = pnRender::getInstance('POBMember');
  
    //check is export
    $export = FormUtil::getPassedValue ('export', false);
    $button_export = FormUtil::getPassedValue ('button_export', false);
    $button_export_x = FormUtil::getPassedValue ('button_export_x', false);
      if ($export || $button_export || $button_export_x){
      $is_export = true;
    }
  
    $class = Loader::loadClassFromModule ('POBMember', $ctrl, true);
    if ($class){
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
          $where = $objectArray->_objWhere;
      }
      if (empty($sort)){
        $sort = $objectArray->_objSort;
      }
      //pagnigation variable
      $filter  = FormUtil::getPassedValue ('filter', 0);
      $offset  = FormUtil::getPassedValue ('startnum', 0);
      //$sort    = FormUtil::getPassedValue ('sort', );
      //Split page
      $pagesize = 100;
      $pager = array();
      $pager['numitems']     = $objectArray->getCount ($where , true);
      $pager['itemsperpage'] = $pagesize;
      $render->assign ('startnum', $offset);
      $render->assign ('pager', $pager);
      $objectArray->get ($where, $sort , $offset, $pagesize);
      //assign to view
      $render->assign('objectArray', $objectArray->_objData);
    }
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }

      /**
        * for delete object for database by specify id
        */
  function POBMember_admin_delete() {
    POBMember_permission();
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    $forward = FormUtil::getPassedValue ('forward', null , 'GET');
  
    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBMember', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');
  
      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();
  
      if($forward){
        $list_url = pnModURL('POBMember', 'admin', 'list' , array('ctrl'   => $ctrl
                                                                )
                            );
      }else{
        $list_url = pnModURL('POBMember', 'admin', 'list' , array('ctrl'=>$ctrl
                                                                )
                            );
      }
  
      if (method_exists($object,'genForward')){
        $forwar_url = $object->genForward();
        pnRedirect($forwar_url);
      }else{
        pnRedirect($list_url);
      }
      die;
    }
  }

  function getUserByUID($uid){

    $pntables = pnDBGetTables();

    $tableUsers  = $pntables['users'];
    $columnUsers = $pntables['users_column'];

    $sql = "SELECT
              $tableUsers.$columnUsers[uname],
              $tableUsers.$columnUsers[pass],
              $tableUsers.$columnUsers[email]
            FROM
              $tableUsers
            WHERE
              $tableUsers.$columnUsers[uid] = $uid
            ";

    //echo $sql; exit;
    $column = array("uname", "pass","email");
    $result = DBUtil::executeSQL($sql);
    $objectUserArray = DBUtil::marshallObjects ($result, $column);

    return $objectUserArray;
  }

  function getUserPropertyByUID($uid){

    $pntables = pnDBGetTables();

    $tableUserProperty  = $pntables['user_property'];
    $columnUserProperty = $pntables['user_property_column'];

    $tableUserData  = $pntables['user_data'];
    $columnUserData = $pntables['user_data_column'];

    $sql = "SELECT
              $tableUserProperty.$columnUserProperty[prop_label] ,
              $tableUserData.$columnUserData[uda_value] 
            FROM
              $tableUserData, $tableUserProperty
            WHERE
              $tableUserData.$columnUserData[uda_propid] = $tableUserProperty.$columnUserProperty[prop_id]
            AND
              $tableUserData.$columnUserData[uda_uid] = $uid
            ";

    //echo $sql; exit;
    $column = array("label","value");
    $result = DBUtil::executeSQL($sql);
    $propertyArray = DBUtil::marshallObjects ($result, $column);

    $rePropertyArray = array();
    foreach($propertyArray as $item){
      $rePropertyArray[$item['label']] = $item['value'];
    }
    return $rePropertyArray;
  }


  function POBMember_admin_activate() {
    POBMember_permission();      
    $ctrl = FormUtil::getPassedValue ('ctrl', false, 'REQUEST');
    $func = FormUtil::getPassedValue ('func', false, 'REQUEST');
    $status = FormUtil::getPassedValue ('status', false, 'REQUEST');
    $uid = FormUtil::getPassedValue ('uid', false, 'REQUEST');

    if($uid){
      $pntables = pnDBGetTables();
      //var_dump($pntables); exit;
      //Select member data 
      $tableMember  = $pntables['pobmember_member'];
      $columnMember = $pntables['pobmember_member_column'];
      $sql = "SELECT
                $tableMember.$columnMember[uid]
              FROM
                $tableMember
              WHERE
                $tableMember.$columnMember[uid] = $uid
              ";
      $column = array("uid");
      $result = DBUtil::executeSQL($sql);
      $objectUserArray = DBUtil::marshallObjects ($result, $column);
      //var_dump($status); exit;

      if($objectUserArray[0]["uid"]){

        //echo "status : ".$status;
        //Update statement
        $obj = array('status' => $status);
        $where    = "WHERE $tableMember.$columnMember[uid]=".$uid;
        DBUtil::updateObject ($obj, 'pobmember_member', $where);

      }else{
        //Insert statement
        $hotelcode = "POBHT".sprintf("%06d",$uid);
        $obj = array('uid'    => $uid,
                     'status' => $status,
                     'hotelcode' => $hotelcode
               );
        // do the insert
        DBUtil::insertObject($obj, 'pobmember_member');

        //Create sub domain
        $userProperty = getUserPropertyByUID($uid);

        $user = getUserByUID($uid);


        //var_dump($user); echo "<br>";

        //var_dump($userProperty['DomainName']); exit;
        createSubDomain($hotelcode, $userProperty['HotelName'], $userProperty['DomainName'], $user[0]['uname'], $user[0]['pass'], $user[0]['email']);
        
        //Send mail
        sendActivateAccountMail($userProperty['HotelName'], $userProperty['DomainName'], $user[0]['uname'], $user[0]['pass'], $user[0]['email']);
      }
    }
    //exit;
    $list_url = pnModURL('POBMember', 'admin', 'list' , array('ctrl'=>$ctrl));
    pnRedirect($list_url);
    die;

  }


  function createSubDomain($hotelcode, $sitename, $subdomain, $username ,$password, $email){
    if (!($class = Loader::loadClass('SubdomainCreator', "modules/POBMember/pnincludes"))){
      return LogUtil::registerError ('Unable to load class [SubdomainCreator] ...');
    }
      
    $form = FormUtil::getPassedValue ('form', false, 'REQUEST');
    $obj = new SubdomainCreator();

     // var_dump($obj); exit;
    //$obj->makedb($dbname,$username,$password,$email);
    $obj->makedb($hotelcode, $sitename, $subdomain, $username, $password, $email);
    $obj->sqlDump();
    //exit;
  }

  function sendActivateAccountMail($sitename, $subdomain, $username ,$password, $email){

    //Message to Customerhttp://www.youtube.com/watch?v=KwD5N3oYgvE
    $subject = "Your account has been activated.";
    $message = "Your account has been activated.\n
                You can login at http://".$subdomain.".phuketcity.com\n
              ";
    $fromname = "phuketcity";
    $fromaddress = "info@phuketcity.com";

    //var_dump($email); exit;
    if(isset($email)){
      $result = pnModAPIFunc( 'Mailer', 
                              'user', 
                              'sendmessage', 
                              array('fromname' => $fromname, 
                                    'fromaddress' => $fromaddress, 
                                    'toaddress' => $email, 
                                    'subject' => $subject, 
                                    'body' => $message, 
                                    'html' => true,
                                    'charset' => 'UTF-8'
                              )
                );
    }

    //Message to Customerhttp://www.youtube.com/watch?v=KwD5N3oYgvE
    $adminSubject = "Admin has activated email.";
    $adminMessage = "Phuketcity has activated customer account.\n
                     Domain : http://".$subdomain.".phuketcity.com\n
                     Username : $username\n
                  ";
    $fromAdminname = "Admin phuketcity";
    $fromAdminAddress = "info@phuketcity.com";
    $toAdminAddress = "ottowan@gmail.com";

    //var_dump($email); exit;
    $result = pnModAPIFunc( 'Mailer', 
                            'user', 
                            'sendmessage', 
                            array('fromname' => $fromAdminname, 
                                  'fromaddress' => $fromAdminAddress, 
                                  'toaddress' => $toAdminAddress, 
                                  'subject' => $adminSubject, 
                                  'body' => $adminMessage, 
                                  'html' => true,
                                  'charset' => 'UTF-8'
                            )
              );

  }

?>