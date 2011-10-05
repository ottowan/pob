<?php
  /**
  * //////////////////////////////////////////////////
  * auto execute , for initialize config variable
  * this function auto call every page has been fetch
  */
  function Gallery_permission() {
    // Security check
    //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN
    if (!SecurityUtil::checkPermission('Gallery::', '::', ACCESS_ADMIN)) {
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
  function Gallery_admin_main() {
    Gallery_permission();
    return Gallery_admin_form();
  }





  /**
  * display page with class that extend Object Array
  */
  function Gallery_admin_form() {
    Gallery_permission();

    //Get hotelCode["hotelcode"]
    $hotelCode = pnModAPIFunc('POBHotel', 'user', 'getHotelCode', null);

    //print_r($hotelCode); pnShutdown(); exit;
    //Create gallery directory
    $galleryPath = './gallery/';
    mkdir($galleryPath, 777, true);

    //Create files directory
    $filesPath = './gallery/files/';
    mkdir($filesPath, 777, true);

    //Create thumbs directory
    $thumbsPath = './gallery/thumbnails/';
    mkdir($thumbsPath, 777, true);

    //Create hotel files directory
    $hotelFilePath = './gallery/files/'.$hotelCode["hotelcode"]."/";
    mkdir($hotelFilePath, 777, true);

    //Create hotel thumbnails directory
    $hotelThumbPath = './gallery/thumbnails/'.$hotelCode["hotelcode"]."/";
    mkdir($hotelThumbPath, 777, true);

    $render = pnRender::getInstance('Gallery');
    $render->assign('hotelcode', $hotelCode["hotelcode"]);
    return $render->fetch('admin_form_image.htm');
  }

  /**
  * display page with class that extend Object Array
  */
  function Gallery_admin_list() {
    Gallery_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Image' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');

    $is_export = false;

    $pagesize = pnModGetVar ('Gallery', 'pagesize') ? pnModGetVar ('Gallery', 'pagesize') : 100;
    $render = pnRender::getInstance('Gallery');

    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/Gallery/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/Gallery/pnlang/' . $lang );
    }else if (file_exists('modules/Gallery/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/Gallery/pnlang/eng' );
    }

    //check is export
    $export = FormUtil::getPassedValue ('export', false);
    $button_export = FormUtil::getPassedValue ('button_export', false);
    $button_export_x = FormUtil::getPassedValue ('button_export_x', false);
      if ($export || $button_export || $button_export_x){
      $is_export = true;
    }

    $class = Loader::loadClassFromModule ('Gallery', $ctrl, true);
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



  function Gallery_admin_upload() {

    //<form action="<!--[pnmodurl modname='Gallery' type='admin' func='upload']-->" method="POST" enctype="multipart/form-data">
    Gallery_permission();
    //Get hotelcode ["hotelcode"]
    $hotelCode = pnModAPIFunc('POBHotel', 'user', 'getHotelCode', null);


    Loader::loadClass('UploadHandler',"modules/Gallery/pnincludes");

    $script_dir = dirname(__FILE__);
    $script_dir_url = dirname($_SERVER['PHP_SELF']);
    $options = array(
        'upload_dir' => $script_dir.'/files/'.$hotelcode["hotelcode"],
        'upload_url' => $script_dir_url.'/files/'.$hotelcode["hotelcode"],
        'thumbnails_dir' => $script_dir.'/thumbnails/'.$hotelcode["hotelcode"],
        'thumbnails_url' => $script_dir_url.'/thumbnails/'.$hotelcode["hotelcode"],
        'thumbnail_max_width' => 80,
        'thumbnail_max_height' => 80,
        'field_name' => 'file'
    );


    $upload_handler = new UploadHandler($options);

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'HEAD':
        case 'GET':
            $upload_handler->get();
            break;
        case 'POST':
            $upload_handler->post();
            break;
        case 'DELETE':
            $upload_handler->delete();
            break;
        default:
            header('HTTP/1.0 405 Method Not Allowed');
    }
  }
?>