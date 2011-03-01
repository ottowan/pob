<?php
class FileUtil{

  public static function createFile($file){
    return fopen($file, 'w');;
  }

  public static function writeFile($file, $text){
    fwrite($file, $text);
  }

  public static function readFile(){
    
  }


  public static function updateFile(){
    
  }

  public static function deleteFile(){

  }

  public static function moveFile(){
    
  }


  public static function copyFile(){
    
  }

  public static function isFile($file){
    (file_exists($file))? $isFile = true : $isFile = false;
    return $isFile;
  }


}

?>