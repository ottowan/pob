<?php
//config
Loader::loadFile('config.php', "modules/InnoGallery");
//load CSVReader class
Loader::loadClass('CSVReader', "modules/InnoGallery/pnincludes");
 /**
  * initialise module
  *
  */
function InnoGallery_init() {
  if (!DBUtil::createTable('innogallery_albums')) {
      return false;
  }
/*
  if (!DBUtil::createTable('innogallery_pictures')) {
      return false;
  }
*/
  if (!DBUtil::createTable('innogallery_comments')) {
      return false;
  }

  init_category();
  //create gallery dir
  $gallery_root = GALLERY_ROOT;
  if (!empty($gallery_root)){
    mkdir(GALLERY_ROOT,0777,true);
  }
  //copy file
  $src = "modules/InnoGallery/pnincludes/flashgallery.php";
  $des = FILE_SCANER_PATH;
  if (!empty($des)){
    $res = copy($src, $des);
  }

  return true;
}

function InnoGallery_delete() {
  // drop table
  DBUtil::dropTable('innogallery_albums');
  //DBUtil::dropTable('innogallery_pictures');
  DBUtil::dropTable('innogallery_comments');
  return true;
}


function init_category(){
  $dataArray = CSVReader::readcsv('modules/InnoGallery/data/albums.csv');
  if ($dataArray){
    DBUtil::insertObjectArray($dataArray, 'innorssnews_category', true);
    unset($dataArray);
  }
}

