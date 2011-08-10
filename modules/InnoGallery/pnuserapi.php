<?php
//load common configuration
Loader::loadClass('Thumbnail','modules/InnoGallery/pnincludes');

/**
 * for upload file into server by specify full path
 *   $result = pnModAPIFunc('InnoGallery', 'user', 'uploadFile',
 *                         array( 'files' => $_FILES[icon2d] ,
 *                                'path' => '',
 *                                'output_name' => [optional] the output file name));
 */
 
function InnoGallery_userapi_uploadFile($args)
{
  //TODO : upload icon not yet complete
  extract($args);
  if ( $files && $path){
    $uploaddir = $path;
    $tmp_name = $files["tmp_name"];
    $name = $files["name"];
    $size = $files["size"];
    $type = $files["type"];
    $ext = strtolower(ereg_replace(".*\.(.*)$","\\1",$name));
    if ($ext == 'php'){
      return false;
    }
    mkdir($uploaddir, 0777 ,true);
    if (!empty($output_name)){
      $full_dir = $uploaddir . '/' . $output_name;
    }else{
      $full_dir = $uploaddir . '/' . $name;
    }
    $full_dir = $uploaddir . '/' . $output_name;
    move_uploaded_file($tmp_name,$full_dir);
  }
}

/**
* upload image and automatic resize from HTMLForm store in 
* @param  $args   HTMLFile element
* @return bool    true on success else fail
* example
* <code>
* <form ...>
*   <input name="images[]" type="file" /><br />
* </form>
* </code>
* <code>
*  $result = pnModAPIFunc('InnoGallery', 'user', 'uploadImage',
*                         array( 'id'    => '1',
*                                'files' => $_FILES[images] ,
*                                'path'  => '' ,
*                                'photo_path' => '' [optional],
*                                'thumb_path' => '' [optional],
*                                'is_rename' => 'true | false'  [optional] #if true will auto generate name,
                                 'is_createthumb' => 'true | false'  [optional] #if true createthumb,
                                 'is_createfirstimage' => 'true | false' ));
* </code>
*/
function InnoGallery_userapi_uploadImage($args)
{
  extract($args);
  

  $limit_size = UPLOAD_IMAGE_LIMIT_SIZE ; //file size limit at 800kb
  $limit_file_type = UPLOAD_IMAGE_LIMIT_FILE_TYPE; //any image file type
  $image_size = UPLOAD_IMAGE_LARGE_SIZE;
  $thumb_image_size = UPLOAD_IMAGE_SMALL_SIZE;
  if ($path && !$is_createthumb){
    $photo_path = $path;
  }
  if ($id){
    $uploaddir = $photo_path . '/' . $id;
    $thumbdir = $thumb_path . '/' . $id;
  }else{
    $uploaddir = $photo_path ;
    $thumbdir = $thumb_path ;
  }
  $quality  = 100;

  mkdir("$uploaddir", 0777 ,true);
  mkdir("$thumbdir", 0777 , true);
  
  foreach ($files["error"] as $key => $error){
    if ($error == 0){
      
      $img_count++;
      $tmp_name = $files["tmp_name"][$key];
      $name = $files["name"][$key];
      $size = $files["size"][$key];
      $type = $files["type"][$key];
      $ext = strtolower(ereg_replace(".*\.(.*)$","\\1",$name));
      //fix bug 2009-01-05 001
      if ($ext == 'jpeg'){
        $ext = 'jpg';
      }
      if (!ereg($limit_file_type,$type)){
          return false;
      }

      if ($size > $limit_size){
         return false;
      }
      /*rename
      if ($is_rename){
        $output_filename = uniqid('image_').'.'.$ext;
      }else{
        $output_filename = strtolower($files["name"][$key]);  
      }
      */
      /*same name*/
      $output_filename = strtolower($files["name"][$key]);

      $full_photo_dir = $uploaddir . '/' .$output_filename;
      $full_thumb_dir = $thumbdir . '/' .$output_filename;
      //echo $full_photo_dir . " uploaded <br />";
      //upload file to server
      move_uploaded_file($tmp_name,$full_photo_dir);
      
      //get image size
      list($w,$h) = getimagesize($full_photo_dir);
      //echo "size $w , $h";
/*      if ($w > $image_size){
        //echo "resize";
        $photo=new thumbnail($full_photo_dir);			// generate image_file, set filename to resize
        $photo->size_auto($image_size);					// set the biggest width or height for thumbnail
        $photo->jpeg_quality($quality);	
        $photo->save($full_photo_dir);
        $photo = null;
      }
*/
      //create thumb.jpg
      if ($img_count == 1 && $is_createfirstimage){
        $mini=new thumbnail($full_photo_dir);			// generate image_file, set filename to resize
        $mini->size_auto($thumb_image_size);					// set the biggest width or height for thumbnail
        $mini->jpeg_quality($quality);				
        //$mini->show();						// show your thumbnail
        //$mini->save($full_thumb_dir);
        $mini->save($thumbdir . '/thumb.jpg' );
        $mini = null;
      }
      //create other thumb
      if ($is_createthumb){
        $mini=new thumbnail($full_photo_dir);			// generate image_file, set filename to resize
        $mini->size_auto($thumb_image_size);					// set the biggest width or height for thumbnail
        $mini->jpeg_quality($quality);				
        $mini->save($full_thumb_dir);
        $mini = null;
      }
                
  }//end if
}
  return true;
}

/**
* id    the albums id
* name  the file name
* size  size of image
*/
function InnoGallery_userapi_setThumbImage($args){
  extract($args);
  if ($id && $name && $size){
    $quality  = 100;
    $thumbdir = $path . '/' . $id ;
    $mini=new thumbnail($thumbdir. '/' . $name);			// generate image_file, set filename to resize
    $mini->size_auto($size);					// set the biggest width or height for thumbnail
    $mini->jpeg_quality($quality);				
    $mini->save($thumbdir . '/thumb.jpg' );
    $mini = null;
  }
  return true;
}
/**
* delete image by specified id or name
*example
*<code>
*  //delete the image by specified name
*  $result = pnModAPIFunc('InnoGallery', 'user', 'deleteImage',
*                         array( 'id'    => '1', 'name' => 'test.jpg'));
*
*  //delete all image store in id = 1
*  $result = pnModAPIFunc('InnoGallery', 'user', 'deleteImage',
*                         array( 'id'    => '1',
                                 'photo_path' => '',
                                 'thumb_path' => ''));
*</code>
*/
function InnoGallery_userapi_deleteImage($args)
{
  extract($args);
  if ($id){
    $uploaddir = $photo_path . '/' . $id;
    $thumbdir = $thumb_path . '/' . $id;

    if ($id && $name){
      @unlink($uploaddir . '/' . $name);
      @unlink($thumbdir . '/' . $name);
    }else if ($id){
      rm_recurse($uploaddir);
      rm_recurse($thumbdir);
    }
  }
  return true;
}

/*
* get image for specified id
* example
*<code>
*  $result = pnModAPIFunc('InnoGallery', 'user', 'getImage',
*                         array( 'id'   => '1',
                                 'path' => 'resource/image/photo' #scan path));
*  print_r($result);
*</code>
*/
function InnoGallery_userapi_getImage($args)
{
  extract($args);
  if ($id){
    $rootDir = $path . '/' . $id;
  }else{
    $rootDir = $path ;
  }
  $allowext = array("jpg","jpeg","gif" , "png");
  return scanDirectories($rootDir,$allowext);
  //return true;
}

/**
* set thumb image by specify name and id
* $result = pnModAPIFunc('InnoForum', 'user', 'setStatus',
*                         array( 'id'     => '1', 
*                                'status' => 0 | 1);
*/
function InnoGallery_userapi_setStatus($args){
  extract($args);
  if ($field){
    $object = array('id' => $id , $field => $status);
  }else{
    $object = array('id' => $id , 'status' => $status);
  }
  if($table){
    return DBUtil::updateObject ($object , $table);
  }
  return false;
}

/**
 * rm_recurse
 * @description Remove recursively. (Like `rm -r`)
 * @see Comment by davedx at gmail dot com on { http://us2.php.net/manual/en/function.rmdir.php }
 * @param file {String} The file or folder to be deleted.
 **/
function rm_recurse($file) {
    if (is_dir($file) && !is_link($file)) {
        foreach(glob($file.'/*') as $sf) {
            if ( !rm_recurse($sf) ) {
                error_log("Failed to remove $sf\n");
                return false;
            }
        }
        return rmdir($file);
    } else {
        return unlink($file);
    }
}


/**
* scan file contain in directory
* <code>
* $rootDir = "www";
* $allowext = array("zip","rar","html");
* $files_array = scanDirectories($rootDir,$allowext);
* print_r($files_array);
* </code>
*/
function scanDirectories($rootDir, $allowext, $allData=array()) {
    $dirContent = scandir($rootDir);
    foreach($dirContent as $key => $content) {
        $path = $rootDir.'/'.$content;
        $ext = substr($content, strrpos($content, '.') + 1);
        
        if(in_array($ext, $allowext)) {
            if(is_file($path) && is_readable($path)) {
                $allData[] = $content;//$path;
            }elseif(is_dir($path) && is_readable($path)) {
                // recursive callback to open new directory
                $allData = scanDirectories($path, $allData);
            }
        }
    }
    return $allData;
}
?>