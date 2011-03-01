<?php
class DirectoryUtil{

  public static function createDirectory($dir){
    mkdir($dir,0777,TRUE);
    return DirectoryUtil::isDirectory($dir);
  }

  public static function readDirectory(){
    
  }

  public static function updateDirectory(){
    
  }
  
  public static function deleteDirectory($dir) { 
    if (!file_exists($dir)) 
      return true; 
    if (!is_dir($dir) || is_link($dir)) 
      return unlink($dir); 

    foreach (scandir($dir) as $item) { 
        if ($item == '.' || $item == '..') continue; 
        if (!DirectoryUtil::deleteDirectory($dir . "/" . $item)) { 
            chmod($dir . "/" . $item, 0777); 
            if (!DirectoryUtil::deleteDirectory($dir . "/" . $item)) 
              return false; 
        }
    } 
    return rmdir($dir); 
  } 

  public static function isDirectory($dir){
    (is_dir($dir))? $isCreateDirectory = true : $isCreateDirectory = false;
    return $isCreateDirectory;
  }

  public static function moveDirectory(){
    
  }

  public static function copyDirectory(){
    
  }

}

?>