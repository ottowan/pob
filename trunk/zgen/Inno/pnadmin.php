<?php
  /**
  * //////////////////////////////////////////////////
  * auto execute , for initialize config variable
  * this function auto call every page has been fetch
  */
  function Inno_permission() {
    // Security check
    //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN
    if (!SecurityUtil::checkPermission('Inno::', '::', ACCESS_ADMIN)) {
        LogUtil::registerPermissionError(pnModUrl('Users','user','loginscreen'));
    }
  }

  /**
  * smarty template auto call before render
  */
  function _preRender(&$render){
    $lang    = FormUtil::getPassedValue ('lang', false , 'GET');
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');

    $render->assign ('_GET', $_GET);
    $render->assign ('_POST', $_POST);
    $render->assign ('_REQUEST', $_REQUEST);

    $render->assign('ctrl', $ctrl);

    if ($lang){
      $render->assign('lang', $lang);
    }else{
      $render->assign('lang', pnUserGetLang());
    }
    $render->assign('access_edit', true);
  }

  /**
  * Main user function, simply return the index page.
  * @author Parinya Bumrungchoo
  * @return string HTML string
  */
  

  function Inno_admin_main() {
    Inno_permission();
    return Inno_admin_list();
  }

  /**
  * display page with out class loader
  */
  function Inno_admin_page() {
      Inno_permission();
      // the class name
          = FormUtil::getPassedValue ('ctrl', 'home' , 'GET');
      // the method of request for edit or view enum[ view | form | delete | list | page]
        = FormUtil::getPassedValue ('func', 'page' , 'GET');
       = pnRender::getInstance('Inno');
      _preRender();
      return ('admin_'..'_'.strtolower().'.htm');
  }


  }
?>