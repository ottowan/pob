<?php
  include "util/DirectoryUtil.class.php";
  include "util/FileUtil.class.php";
  include "util/MySQLUtil.class.php";
  include "classes/PNTables.class.php";
  include "classes/PNVersion.class.php";
  include "classes/PNInit.class.php";
  include "classes/PNAdmin.class.php";
  include "classes/PNAdminForm.class.php";
  include "classes/PNUser.class.php";
  include "classes/PNUserForm.class.php";
  include "classes/ClassGenerator.class.php";


  $url = "http://localhost/pob/zgen/upload/mindmap.mm";

  //Use curl for get data from link.
  $client = curl_init($url);
  curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($client);
  curl_close($client);
  //var_dump(strstr($response, "Not Found")); exit;

  if (strstr($response, 'Not Found')) {
    echo "[<B>Error</B> : Mindmap not found from http://localhost/zgen/upload/mindmap.mm";
  } else {

    //Read response to object
    $mindmap = simplexml_load_string ($response);
    $module = $mindmap->node->attributes()->TEXT;

    echo "[<B>Generate ::</B> $module module from $url]<br>";
    echo "<BR>/////////////////////// Create the $module Project Directory ////////////////////////////<BR>";
    //Make new directory for module generator
    if(!DirectoryUtil::isDirectory($module)){
      DirectoryUtil::createDirectory($module);
      echo "$module directory is created.<br><br>";
    }else{
      DirectoryUtil::deleteDirectory($module);
      echo "$module directory is deleted.<br>";
      DirectoryUtil::createDirectory($module);
      echo "$module directory is created.<br><br>";
    }


    echo "<BR>/////////////////////// Create the installation file ////////////////////////////<BR>";
    //Create pnversion.php file
    $pnversion = new PNVersion($module, $description);
    $pnversion->createPNVersionFile();

    //Create pninit.php file
    $pninit = new PNInit($module, $mindmap);
    $pninit->createPNInitFile();

    //Create pntables.php file
    $pntable = new PNTables($module, $mindmap);
    $pntable->createPNTableFile();


    echo "<BR>/////////////////////// Create the controller file ////////////////////////////<BR>";
    //Create pnadmin.php file
    $pnadmin = new PNAdmin($module);
    $pnadmin->createPNAdminFile();


    //Create pnadminform.php file
    $pnadminform = new PNAdminForm($module, $mindmap);
    $pnadminform->createPNAdminFormFile();

    //Create pnuserform.php file
    $pnuser = new PNUser($module, $mindmap);
    $pnuser->createPNUserFile();

    //Create pnuserform.php file
    $pnuserform = new PNUserForm($module, $mindmap);
    $pnuserform->createPNUserFormFile();

    echo "<BR>/////////////////////// Create the model(classes) file ////////////////////////////<BR>";
    //Create Class file
    $classes = new ClassGenerator($module, $mindmap);
    $classes->createClassFile();

    unset($mindmap);
    unset($pnversion);
    unset($pninit);
    unset($pntable);
    unset($pnadmin);
    unset($pnadminform);
    unset($pnuser);
    unset($pnuserform);
    unset($classes);
  }
?>

