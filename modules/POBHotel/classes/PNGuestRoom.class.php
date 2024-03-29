<?php
  class PNGuestRoom extends PNObject {
    function PNGuestRoom($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_guest_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      
      
      $this->_init($init, $where);
      
    }
/* 
    function updatePostProcess(){
          $id = $this->_objData['id'];
          //$this->uploadFiles($id);
          
    }
 
    function save(){
      parent::save();
      $this->sendNotify();
    }
    private function sendNotify(){
      if (!($class = Loader::loadClass('OTA_HotelAvailNotifRQ', "modules/POBHotel/pnincludes"))){
        return LogUtil::registerError ('Unable to load class [OTA_HotelAvailNotifRQ] ...');
      }
      $HotelAvailNotifRQ = new OTA_HotelAvailNotifRQ();;
      $HotelAvailNotifRQ->sendContent();
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
      $filepath = $imagespathbyid."room/";
      $thumbspath = $thumbspathbyid."room/";
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
            $image_id = DBUtil::selectFieldMax( 'pobhotel_room_image', 'id', 'MAX', '');
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
                            'room_id' => $id,
                            'filename'   => $filename_temp,
                            'filesize'   => $filesize,
                            'filetype'   => $filetype,
                            'filepath'   => $filepath,
                            'thumbname'  => $thumbname_temp,
                            'thumbsize'  => $thumbsize,
                            'thumbtype'  => $filetype,
                            'thumbpath'   => $thumbspath
                           );

          DBUtil::insertObject($objects,'pobhotel_room_image');
        }
      }
    }
      }
      }
*/


  }
?>