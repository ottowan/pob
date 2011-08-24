<?php
  /**
  * //////////////////////////////////////////////////
  * auto execute , for initialize config variable
  * this function auto call every page has been fetch
  */
  function POBHotel_permission() {
    // Security check
    //we are allow for admin access level , see in config.php variable name ACCESS_ADMIN
    if (!SecurityUtil::checkPermission('POBHotel::', '::', ACCESS_ADMIN)) {
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
  function POBHotel_admin_main() {
    POBHotel_permission();
    return POBHotel_admin_page();
  }

  /**
  * display page with out class loader
  */
  function POBHotel_admin_page() {
    POBHotel_permission();
    return POBHotel_admin_form();
  }

  /**
  * display page with class that extend Object
  */
  function POBHotel_admin_view() {
    POBHotel_permission();
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


    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBHotel/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/' . $lang );
    }else if (file_exists('modules/POBHotel/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/eng' );
    }

    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
    $render = pnRender::getInstance('POBHotel');

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl, false)))
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
  function POBHotel_admin_form() {
    POBHotel_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'Hotel' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'form' , 'GET');
    //$id the id no if edit form
    $id      = FormUtil::getPassedValue ('id', 1 , 'GET');
    //pagnigation variable
    $filter  = FormUtil::getPassedValue ('filter', 0);
    $offset  = FormUtil::getPassedValue ('startnum', 0);
    $sort    = FormUtil::getPassedValue ('sort', '');
    $where   = '';
    $step    = FormUtil::getPassedValue ('step', null , 'GET');


    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBHotel/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/' . $lang );
    }else if (file_exists('modules/POBHotel/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/eng' );
    }

    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
    $render = pnRender::getInstance('POBHotel');
    $mode = null;
    //load class
    if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl, false)))
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
  function POBHotel_admin_list() {
    POBHotel_permission();
    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'room' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'list' , 'GET');

    $is_export = false;

    $pagesize = pnModGetVar ('POBHotel', 'pagesize') ? pnModGetVar ('POBHotel', 'pagesize') : 100;
    $render = pnRender::getInstance('POBHotel');

    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBHotel/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/' . $lang );
    }else if (file_exists('modules/POBHotel/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/eng' );
    }

    //check is export
    $export = FormUtil::getPassedValue ('export', false);
    $button_export = FormUtil::getPassedValue ('button_export', false);
    $button_export_x = FormUtil::getPassedValue ('button_export_x', false);
      if ($export || $button_export || $button_export_x){
      $is_export = true;
    }

    $class = Loader::loadClassFromModule ('POBHotel', $ctrl, true);
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
  function POBHotel_admin_delete() {
    POBHotel_permission();
    $ctrl    = FormUtil::getPassedValue ('ctrl', false , 'GET');
    $id      = FormUtil::getPassedValue ('id', null , 'GET');
    $forward = FormUtil::getPassedValue ('forward', null , 'GET');

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', $ctrl, false)))
        return LogUtil::registerError ('Unable to load class [$ctrl] ...');

      $object  = new $class ();
      $object->_objData[$object->_objField] = $id;
      $object->delete ();

      if($forward){
        $list_url = pnModURL('POBHotel', 'admin', 'list' , array('ctrl'   => $ctrl
                                                                )
                            );
      }else{
        $list_url = pnModURL('POBHotel', 'admin', 'list' , array('ctrl'=>$ctrl
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


  /**
  * Create the room rate
  */
  function POBHotel_admin_createRate() {
    POBHotel_permission();
    $ctrl = FormUtil::getPassedValue ('ctrl', false);
    $func = FormUtil::getPassedValue ('func', false);
    $form = FormUtil::getPassedValue ('form', false);

    $room_id    = FormUtil::getPassedValue ('room_id', false);
    $season_id  = FormUtil::getPassedValue ('season_id', false);
    $room_rate  = FormUtil::getPassedValue ('room_rate', false);
    $one_bed    = FormUtil::getPassedValue ('one_bed', false);
    $two_bed    = FormUtil::getPassedValue ('two_bed', false);
    $single_bed = FormUtil::getPassedValue ('single_bed', false);


    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBHotel/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/' . $lang );
    }else if (file_exists('modules/POBHotel/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBHotel/pnlang/eng' );
    }


    if($form){
      for($i=0; $i < count($form['room_id'])  ; $i++){
        //$valArray = explode("@", $key);
        $obj = array(
                        'season_id'=>$form['season_id'],
                        'room_id'=>$form['room_id'][$i],
                        'room_rate'=>$form['room_rate'][$i],
                        'one_bed'=>$form['one_bed'][$i],
                        'two_bed'=>$form['two_bed'][$i],
                        'single_bed'=>$form['single_bed'][$i]
                 );

        //Do the insert
        DBUtil::insertObject($obj, 'pobhotel_rate');
        unset($obj);
      }
    }

    $render = pnRender::getInstance('POBHotel');
    _languageRender($render);
    return $render->fetch('admin_'.$func.'_'.strtolower($ctrl).'.htm');
  }


  function POBHotel_admin_update() {
    POBHotel_permission();
    $ctrl = FormUtil::getPassedValue ('ctrl', false);
    $func = FormUtil::getPassedValue ('func', false);
    $status = FormUtil::getPassedValue ('status', false);
    $id = FormUtil::getPassedValue ('id', false);


    if($id){
      $pntables = pnDBGetTables();
      $column   = $pntables['pobhotel_member_column'];
      $obj = array('status' => $status);
      $where    = "WHERE $column[id]=".$id;

      DBUTil::updateObject ($obj, 'pobhotel_member', $where);
    }


    if($id == 1){
      POBHotel_admin_createDatabase();
    }

/*
    pnRedirect('admin_list_'.strtolower($ctrl).'.htm');
*/
    $render = pnRender::getInstance('POBHotel');
    _languageRender($render);
    return $render->fetch('admin_list_'.strtolower($ctrl).'.htm');
  }

  function POBHotel_admin_createDatabase(){
    if (!($class = Loader::loadClass('SubdomainCreator', "modules/POBHotel/pnincludes")))
      return LogUtil::registerError ('Unable to load class [SubdomainCreator] ...');
    $table = FormUtil::getPassedValue ('table', false, 'REQUEST');
    $obj = new SubdomainCreator();
    $obj->makedb($table);
    $obj->sqlDump();
    exit;
  }


  function POBHotel_admin_report(){

    $startDay = FormUtil::getPassedValue ('startDay', FALSE, 'POST');
    $startMonth = FormUtil::getPassedValue ('startMonth', FALSE, 'POST');
    $startYear = FormUtil::getPassedValue ('startYear', FALSE, 'POST');

    $endDay = FormUtil::getPassedValue ('endDay', FALSE, 'POST');
    $endMonth = FormUtil::getPassedValue ('endMonth', FALSE, 'POST');
    $endYear = FormUtil::getPassedValue ('endYear', FALSE, 'POST');

    //Change thai yeasr to US year
    if(($startYear-543) == date("Y")){
      $startYear = date("Y");
    }else if(($startYear-543) == date("Y")+1){
      $startYear = date("Y")+1;
    }
    if(($endYear-543) == date("Y")){
      $endYear = date("Y");
    }else if(($endYear-543) == date("Y")+1){
      $endYear = date("Y")+1;
    }

    $startDate = $startYear."-".$startMonth."-".$startDay;
    $endDate   = $endYear."-".$endMonth."-".$endDay;

    $hotelArray = pnModAPIFunc('POBHotel', 'user', 'getHotelCode');
    //var_dump($latlonArray); exit;
    $hotelCode  = $hotelArray["hotelcode"];
    //var_dump($hotelCode); exit;



      $render = pnRender::getInstance('POBHotel');

      //Load language
      $lang = pnUserGetLang();
      if (file_exists('modules/POBHotel/pnlang/' . $lang . '/user.php')){
        Loader::loadFile('user.php', 'modules/POBHotel/pnlang/' . $lang );
      }else if (file_exists('modules/POBHotel/pnlang/eng/user.php')){
        Loader::loadFile('user.php', 'modules/POBHotel/pnlang/eng' );
      }
      
    if($hotelCode && $startDay && $endDay){
      Loader::loadClass('BookingReportEndpoint',"modules/POBHotel/pnincludes");
      $roomSearch = new BookingReportEndpoint();
      $roomSearch->setBookingReportXML( $hotelCode, $startDate, $endDate );


      //Get XML for report response
      //header("Content-type: text/xml");
      //echo ($roomSearch->sampleBookingReportXML()); exit;
      
      //XML Response
      //$response = $roomSearch->requestSampleBookingReportXML();
      $response = $roomSearch->getBookingReportXML();
      //header("Content-type: text/xml");
      //echo ($response); exit;

      //Convert xml response to array
      Loader::loadClass('POBReader',"modules/POBHotel/pnincludes");
      $reader = new POBReader();
      $arrayResponse = $reader->xmlToArray($response);
      //print_r($arrayResponse); exit;

      //Extract array for smarty display
      $extractArray = $roomSearch->extractArrayForDisplay($arrayResponse);
      //print_r($extractArray); exit;

      //Repack array for smarty display
      $repackArray = $roomSearch->repackArrayForDisplay($extractArray);
      //print_r($repackArray); exit;


      /////////////////////////////////////////////////
      //Export to CSV file
      /////////////////////////////////////////////////
      $rootCSVPath = "pnTemp/pobhotel_upload/csvfile";
      //Make root csv directory
      if (!is_dir($rootCSVPath)) {
        mkdir($rootCSVPath, 0755);
      }
      $hotelCSVPath = $rootCSVPath."/".$hotelCode;
      //Make hotel directory
      if (!is_dir($hotelCSVPath)) {
        mkdir($hotelCSVPath, 0755);
      }
      $CSVName = $hotelCSVPath."/".mktime().".csv";
      $CSVFileName = str_replace($hotelCSVPath."/","",$CSVName);
      $CSVFileName = str_replace(".csv","",$CSVFileName);
      $fh = fopen($CSVName, "w+") or die("can't open file");
      if($fh){
        $stringData = "\"Booking ID\",\"Name\",\"Phone Number\",\"E-Mail\",\"Address\",\"RoomType\",\"CheckIn Date\",\"CheckOut Date\",\"Comment\"\n";
        fwrite($fh, $stringData);
        foreach($repackArray['HotelReservations'] AS $item){
          foreach($item['RoomStays'] AS $RoomStaysItem){
            if(count($RoomStaysItem['Comment'])>0){
              $Comment = ",\"".$RoomStaysItem['Comment']."\"";
            }else{
              $Comment = "";
            }
            $stringData = "\"".$item['BookingID']."\",\"".$item['Customer']['PersonName']['NamePrefix'].$item['Customer']['PersonName']['GivenName']." ".$item['Customer']['PersonName']['Surname']."\",\"".$item['Customer']['Telephone']['Telephone']['PhoneNumber']."\",\"".$item['Customer']['Email']."\",\"".$item['Customer']['Address']['AddressLine']." ".$item['Customer']['Address']['CityName']." ".$item['Customer']['Address']['StateProv']." ".$item['Customer']['Address']['CountryName']." ".$item['Customer']['Address']['PostalCode']."\",\"".$RoomStaysItem['InvCode']."\",\"".$RoomStaysItem['CheckInDate']."\",\"".$RoomStaysItem['CheckOutDate']."\"$Comment\n";
            fwrite($fh, $stringData);
          }
        }
      }
      fclose($fh);
      /////////////////////////////////////////////////
      //End save data to CSV file
      /////////////////////////////////////////////////

      $render->assign("openFirst", 2 );
      $render->assign("objectArray", $repackArray );
      $render->assign("filePath",$CSVName);
      $render->assign("fileName",$CSVFileName);
      $render->assign("hotelCode",$hotelCode);
      return $render->fetch('admin_list_report.htm');
    }else{
      $render->assign("openFirst", 1 );
      return $render->fetch('admin_list_report.htm');
    }
    
  }
function POBHotel_admin_getReport(){
  $fileName = FormUtil::getPassedValue ('filename', FALSE, 'REQUEST');
  $hotelCode = FormUtil::getPassedValue ('hotelcode', FALSE, 'REQUEST');
  downloadFile("pnTemp/pobhotel_upload/csvfile/".$hotelCode."/".$fileName.".csv");
  exit;
}
function downloadFile( $fullPath ){ 

  // Must be fresh start 
  if( headers_sent() ) 
    die('Headers Sent'); 

  // Required for some browsers 
  if(ini_get('zlib.output_compression')) 
    ini_set('zlib.output_compression', 'Off'); 

  // File Exists? 
  if( file_exists($fullPath) ){ 
    
    // Parse Info / Get Extension 
    $fsize = filesize($fullPath); 
    $path_parts = pathinfo($fullPath); 
    $ext = strtolower($path_parts["extension"]); 
    
    // Determine Content Type 
    switch ($ext) { 
      case "pdf": $ctype="application/pdf"; break; 
      case "csv": $ctype="text/csv"; break; 
      case "exe": $ctype="application/octet-stream"; break; 
      case "zip": $ctype="application/zip"; break; 
      case "doc": $ctype="application/msword"; break; 
      case "xls": $ctype="application/vnd.ms-excel"; break; 
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
      case "gif": $ctype="image/gif"; break; 
      case "png": $ctype="image/png"; break; 
      case "jpeg": 
      case "jpg": $ctype="image/jpg"; break; 
      default: $ctype="application/force-download"; 
    } 

    header("Pragma: public"); // required 
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); // required for certain browsers 
    header("Content-Type: $ctype"); 
    header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" ); 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Length: ".$fsize); 
    ob_clean(); 
    flush(); 
    readfile( $fullPath ); 

  } else 
    die('File Not Found'); 

} 
?>