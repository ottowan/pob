<?php
$allowed_formats = array("jpg", "jpeg", "JPG", "JPEG", "png", "PNG");
$exclude_files = array(
"_derived",
"_private",
"_vti_cnf",
"_vti_pvt",
"vti_script",
"_vti_txt"
); // add any other folders or files you wish to exclude from the gallery.

$path = array();

if(isset($_GET['file_dir'])){
  $file_dir = $_GET['file_dir'];
  $dir=opendir($file_dir);
  while ($file=readdir($dir))
  {
    $ext = substr($file, strpos($file, ".")+1);
    if(array_search($file, $exclude_files)===false && array_search($ext, $allowed_formats)!==false){
      $f='/'.$file;
      $path[]=$f;
    }
  }
closedir($dir);
}
natcasesort($path);

print '<?xml version="1.0" encoding="iso-8859-1"?>';
print '<pics';
print '>';

$directory= $_SERVER['HTTP_HOST'] .$_SERVER['PHP_SELF'];
$directory=dirname($directory).'/'.$file_dir;

foreach ($path as $val) 
print '<pic src="'.'http://'.$directory.$val.'" title="'.$val.'" />';
print "</pics>";
?>