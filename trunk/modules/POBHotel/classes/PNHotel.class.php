<?php
  class PNHotel extends PNObject {
    function PNHotel($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }

    function selectExtendResult(){
      $id = $this->_objData['id'];
      $result = array();
      if ($id){
        //load class HotelAmenityArray
        if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelAmenityArray', false)))
          return LogUtil::registerError ('Unable to load class [HotelAmenityArray] ...');
          
        $amenityObject = new $class;
        $amenityObject->get();
        $result['hotelAmenity'] = $amenityObject->_objData;
        
        //load class HotelLocationArray
        if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelLocationArray', false)))
          return LogUtil::registerError ('Unable to load class [HotelLocationArray] ...');
          
        $locationObject = new $class;
        $locationObject->get();
        $result['hotelLocation'] = $locationObject->_objData;
        
        //load class HotelAttractionArray
        if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelAttractionArray', false)))
          return LogUtil::registerError ('Unable to load class [HotelAttractionArray] ...');
          
        $attractionObject = new $class;
        $attractionObject->get();
        $result['hotelAttraction'] = $attractionObject->_objData;
        
        //load class HotelIndexPointArray
        if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelIndexPointArray', false)))
          return LogUtil::registerError ('Unable to load class [HotelIndexPointArray] ...');
            
        $indexPointObject = new $class;
        $indexPointObject->get();
        $result['hotelIndexPoint'] = $indexPointObject->_objData;
        
        //load class HotelSeason
        if (!($class = Loader::loadClassFromModule ('POBHotel', 'SeasonArray', false)))
          return LogUtil::registerError ('Unable to load class [SeasonArray] ...');
            
        $seasonObject = new $class;
        $seasonObject->get();
        $result['hotelSeason'] = $seasonObject->_objData;
        
        //load class GuestRoomType
        if (!($class = Loader::loadClassFromModule ('POBHotel', 'GuestRoomTypeArray', false)))
          return LogUtil::registerError ('Unable to load class [GuestRoomTypeArray] ...');
            
        $guestRoomTypeObject = new $class;
        $guestRoomTypeObject->get();
        $result['hotelGuestRoomType'] = $guestRoomTypeObject->_objData;
        
      }
      return $result;
    }

    function insertPostProcess(){
      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      $id = $this->_objData['id'];

      if($itemAmenity && $id){
        foreach($itemAmenity as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'amenity_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_amenity');
        unset($obj);
      }

      $itemIndexPoint = FormUtil::getPassedValue ('itemIndexPoint', false);
      $id = $this->_objData['id'];
      if($itemIndexPoint && $id){
        foreach($itemIndexPoint as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'index_point_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_index_point');
        unset($obj);
      }

      $itemAttraction = FormUtil::getPassedValue ('itemAttraction', false);
      $id = $this->_objData['id'];
      if($itemAttraction && $id){
        foreach($itemAttraction as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'attraction_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_attraction');
        unset($obj);
      }


      $itemLocationCategory = FormUtil::getPassedValue ('itemLocationCategory', false);
      if($itemLocationCategory && $id){
        foreach($itemLocationCategory as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'location_category_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_location');
        unset($obj);
      }


        $this->uploadFiles($id);
        //$this->sendNotify();
    }

    function updatePostProcess(){

      $id = $this->_objData['id'];
      $itemLocationCategory = FormUtil::getPassedValue ('itemLocationCategory', false);
      if($itemLocationCategory && $id){
        foreach($itemLocationCategory as $key1=>$vals ){
          foreach($vals AS $key2=>$val){
            $obj[$key1][$key2] = $val;
            $obj[$key1]["id"] = $key1;
          }
        }
        DBUtil::updateObjectArray($obj, 'pobhotel_hotel_location');
        unset($obj);
      }
      
      $itemIndexPoint = FormUtil::getPassedValue ('itemIndexPoint', false);
      if($itemIndexPoint && $id){
        foreach($itemIndexPoint as $key1=>$vals ){
          foreach($vals AS $key2=>$val){
            $obj[$key1][$key2] = $val;
            $obj[$key1]["id"] = $key1;
          }
        }
        DBUtil::updateObjectArray($obj, 'pobhotel_hotel_index_point');
        unset($obj);
      }
      
      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      if($itemAmenity && $id){
        foreach($itemAmenity as $key1=>$vals ){
          foreach($vals AS $key2=>$val){
            $obj[$key1][$key2] = $val;
            $obj[$key1]["id"] = $key1;
          }
        }
        DBUtil::updateObjectArray($obj, 'pobhotel_hotel_amenity');
        unset($obj);
      }
      
      
      $itemAttraction = FormUtil::getPassedValue ('itemAttraction', false);
      if($itemAttraction && $id){
        foreach($itemAttraction as $key1=>$vals ){
          foreach($vals AS $key2=>$val){
            $obj[$key1][$key2] = $val;
            $obj[$key1]["id"] = $key1;
          }
        }
        DBUtil::updateObjectArray($obj, 'pobhotel_hotel_attraction');
        unset($obj);
      }
      
      $this->uploadFiles($id);
      //$this->sendNotify();
      /*
      
      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      //Delete old data
      DBUtil::deleteObjectByID( 'pobhotel_hotel_amenity', $id, 'hotel_id');
      
      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      $id = $this->_objData['id'];
      if($itemAmenity && $id){
        foreach($itemAmenity as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'amenity_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_amenity');
      }
      */

    }

  function uploadFiles($id){
    if ($id && in_array(0,$_FILES['images']['error'])){
      $images = $_FILES['images'];

      //Set directory to store image path
      if($id % 10000 == 0){
        $newNumberOfDirectory = ($id/10000);
        if($newNumberOfDirectory == 0){
          $rootImagePath = "pnTemp/pobhotel_upload/image/large";
          $rootThumbPath = "pnTemp/pobhotel_upload/image/thumb";
        }else{
          $rootImagePath = "pnTemp/pobhotel_upload/image/large".$newNumberOfDirectory;
          $rootThumbPath = "pnTemp/pobhotel_upload/image/thumb".$newNumberOfDirectory;
        }

      }else{
        $rootImagePath = "pnTemp/pobhotel_upload/image/large".floor($id/10000);
        $rootThumbPath = "pnTemp/pobhotel_upload/image/thumb".floor($id/10000);
      }

      //Make root topic image directory 
      if (!is_dir($rootImagePath)) {
        mkdir($rootImagePath, 0755);
      }
      //Make root topic thumb directory
      if (!is_dir($rootThumbPath)) {
        mkdir($rootThumbPath, 0755);
      }

      $imagespathbyid = $rootImagePath."/".$id."/";
      $thumbspathbyid = $rootThumbPath."/".$id."/";


      //Make topic image directory by id
      if (!is_dir($imagespathbyid)) {
        mkdir($imagespathbyid, 0755);
      }
      //Make topic thumb directory by id
      if (!is_dir($thumbspathbyid)) {
        mkdir($thumbspathbyid, 0755);
      }

      //Set directory to store image path by id
      $filepath = $imagespathbyid."hotel/";
      $thumbspath = $thumbspathbyid."hotel/";
      $imagespath=$filepath;

      //Make topic image directory by id
      if (!is_dir($imagespath)) {
        mkdir($imagespath, 0755);

      }
      //Make topic thumb directory by id
      if (!is_dir($thumbspath)) {
        mkdir($thumbspath, 0755);
      }

      foreach ($images["error"] as $key => $error){
        if ($error == 0){
            //Gennerate next id
            $image_id = DBUtil::selectFieldMax( 'pobhotel_hotel_image', 'id', 'MAX', '');
            if($image_id == null){
              $next_id = 1;
            }else{
              $next_id = $image_id+1;
            }

            $tmp_name = $images["tmp_name"][$key];
            $fliename = $images["name"][$key];
            $filetype = $images["type"][$key];
            $filesize = $images["size"][$key];

////////////////////////// get the extension of the file in a lower case format/////////////////////////////////////////////
            $filename = stripslashes($fliename);
            $i = strrpos($filename,".");
            if (!$i) { return ""; }
            $l = strlen($filename) - $i;
            $ext = substr($filename,$i+1,$l);
            $extension = strtolower($ext);

////////////////////////////generate new file&path/////////////////////////////////////////////////////////////////////
            $rootname       = $next_id.time().".".$extension;
            $filename_temp  = "images_".$id.$rootname;
            $thumbname_temp = "thumbs_".$id.$rootname;
            $imgpath        = $imagespath.$filename_temp;
            $tmbpath        = $thumbspath.$filename_temp;
            if($key==0){
              $firstimage_temp = $filename_temp;
            }

//////////////////////////start upload/////////////////////////////////////////////

        $source = $tmp_name;
        $target = $imgpath;
        move_uploaded_file($source, $target);

        //images
        $imagepath = $fliename;
        $save = $imgpath; //This is the new file you saving
        $file = $imgpath; //This is the original file
        list($width, $height) = getimagesize($file) ; 

//////////////////resize image orginal////////////////////
        $modwidth = 600; 
        $diff = $width / $modwidth;
        if (($width > 600) || ($height > 200)) 
        {
          $modheight = $height / $diff; 
          $tn = imagecreatetruecolor($modwidth, $modheight); 
          if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
            $image = imagecreatefromjpeg($file) ; 
          }elseif($filetype == "image/x-png" || $filetype == "image/png"){
            $image = imagecreatefrompng($file) ;
          }elseif($filetype == "image/gif"){
            $image = imagecreatefromgif($file) ;
          }
            imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ; 
        }

        if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
          imagejpeg($tn, $save, 100) ; 
        }elseif($filetype == "image/x-png" || $filetype == "image/png"){
          imagepng($tn, $save) ; 
        }elseif($filetype == "image/gif"){
          copy($file, $save) ; 
        }

//////////////////resize image thumb////////////////////

        $save = $tmbpath; //This is the new file you saving
        $file = $imgpath; //This is the original file

        list($width, $height) = getimagesize($file) ; 

        $modwidth = 100; 
        $diff = $width / $modwidth;
        $modheight = $height / $diff; 
        $tn = imagecreatetruecolor($modwidth, $modheight); 
        if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
          $image = imagecreatefromjpeg($file) ; 
        }elseif($filetype == "image/x-png" || $filetype == "image/png"){
          $image = imagecreatefrompng($file) ;
        }elseif($filetype == "image/gif"){
          $image = imagecreatefromgif($file) ;
        }
        imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ; 

        if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
          imagejpeg($tn, $save, 100) ; 
        }elseif($filetype == "image/x-png" || $filetype == "image/png"){
          imagepng($tn, $save) ; 
        }elseif($filetype == "image/gif"){
          copy($file, $save) ; 
        }


            $objects = array(
                            'id'         => $next_id,
                            'filename'   => $filename_temp,
                            'filesize'   => $filesize,
                            'filetype'   => $filetype,
                            'filepath'   => $filepath,
                            'thumbname'  => $thumbname_temp,
                            'thumbsize'  => $thumbsize,
                            'thumbtype'  => $filetype,
                            'thumbpath'   => $thumbspath
                           );

          DBUtil::insertObject($objects,'pobhotel_hotel_image');
        }
      }
    }
  }

    private function sendNotify(){
      if (!($class = Loader::loadClass('HotelDescContentGenerator', "modules/POBHotel/pnincludes")))
        return LogUtil::registerError ('Unable to load class [HotelDescContentGenerator] ...');
      $obj = new HotelDescContentGenerator($this->_objData['id']);
      $res = $obj->sendContent();
      print $res;

    }
    private function createDatabase(){
      if (!($class = Loader::loadClass('SubdomainCreator', "modules/POBHotel/pnincludes"))){
        return LogUtil::registerError ('Unable to load class [SubdomainCreator] ...');
      }
        
      $form = FormUtil::getPassedValue ('form', false, 'REQUEST');
      $obj = new SubdomainCreator();
      $obj->makedb($form['database_name']);
      $obj->sqlDump();
      exit;
    }
  }
?>