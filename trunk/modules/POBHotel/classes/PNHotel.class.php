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
      $id = $this->objData['id'];
      var_dump($id);
      $result = array();
      if ($id){

      $fieldArray = array('amenity_id');

      $result['hotelAmenity'] = DBUtil::selectObjectArray( 'pobhotel_hotel_amenity',
                                                                "WHERE hotel_amenity_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldArray
      );


      $fieldArray = array('attraction_id');
      $result['hotelAttraction'] = DBUtil::selectObjectArray( 'pobhotel_hotel_attraction',
                                                                "WHERE hotel_attraction_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldArray
      );

      $fieldImageArray = array('image_id');
      /*
      $result['imageHotel'] = DBUtil::selectObjectArray( 'pobhotel_hotel_image',
                                                                "WHERE image_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                array('image_id')
      );
      */
      $fieldLocationArray = array('location_category_id');

      $result['locationCategory'] = DBUtil::selectObjectArray( 'pobhotel_hotel_location',
                                                                "WHERE hotel_location_hotel_id = '$id'",
                                                                '',
                                                                -1,
                                                                -1,
                                                                '',
                                                                null,
                                                                null,
                                                                $fieldLocationArray
      );


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
        
        
        //Send notify to center service.
        $this->sendNotify();


    }

    function updatePostProcess(){

      $id = $this->_objData['id'];
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

      $itemLocationCategory = FormUtil::getPassedValue ('itemLocationCategory', false);
      DBUtil::deleteObjectByID( 'pobhotel_hotel_location', $id, 'hotel_id');

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
                            'hotel_id' => $id,
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
      exit;
    }
  }
?>