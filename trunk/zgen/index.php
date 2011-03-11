<?php
  include "util/DirectoryUtil.class.php";
  include "util/FileUtil.class.php";
  include "util/MySQLUtil.class.php";
  include "classes/PNTablesGenerator.class.php";
  include "classes/PNVersionGenerator.class.php";
  include "classes/PNInitGenerator.class.php";
  include "classes/PNAdminGenerator.class.php";
  include "classes/PNAdminFormGenerator.class.php";
  include "classes/PNUserGenerator.class.php";
  include "classes/PNUserFormGenerator.class.php";
  include "classes/ClassGenerator.class.php";
  include "classes/PNTemplateGenerator.class.php";
  include "classes/PluginsGenerator.class.php";
  include "classes/PNIncludeGenerator.class.php";
  include "classes/PNImagesGenerator.class.php";




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
    $pnversion = new PNVersionGenerator($module, $description);
    $pnversion->createPNVersionFile();

    //Create pninit.php file
    $pninit = new PNInitGenerator($module, $mindmap);
    $pninit->createPNInitFile();

    //Create pntables.php file
    $pntable = new PNTablesGenerator($module, $mindmap);
    $pntable->createPNTableFile();


    echo "<BR>/////////////////////// Create the controller file ////////////////////////////<BR>";
    //Create pnadmin.php file
    $pnadmin = new PNAdminGenerator($module);
    $pnadmin->createPNAdminFile();


    //Create pnadminform.php file
    $pnadminform = new PNAdminFormGenerator($module, $mindmap);
    $pnadminform->createPNAdminFormFile();

    //Create pnuserform.php file
    $pnuser = new PNUserGenerator($module, $mindmap);
    $pnuser->createPNUserFile();

    //Create pnuserform.php file
    $pnuserform = new PNUserFormGenerator($module, $mindmap);
    $pnuserform->createPNUserFormFile();

    echo "<BR>/////////////////////// Create the model(classes) file ////////////////////////////<BR>";
    //Create Class file
    $classes = new ClassGenerator($module, $mindmap);
    $classes->createClassFile();

    echo "<BR>/////////////////////// Create the view(pntemplate) file ////////////////////////////<BR>";
    //Create Class file
    $pntemplate = new PNTemplateGenerator($module, $mindmap);
    $pntemplate->createPNTemplateFile();

    echo "<BR>/////////////////////// Create the plugins file ////////////////////////////<BR>";
    //Create Class file
    $plugins = new PluginsGenerator($module, $mindmap);
    $plugins->createPluginsFile();


    echo "<BR>/////////////////////// Create the pnimages file ////////////////////////////<BR>";
    //Create Class file
    $plugins = new PNImagesGenerator($module, $mindmap);
    $plugins->createPNImagesFile();


    echo "<BR>/////////////////////// Create the PNInclude file ////////////////////////////<BR>";
    //Create Class file
    $pninclude = new PNIncludeGenerator($module, $mindmap);
    $pninclude->createPNIncludeFile();


    unset($mindmap);
    unset($pnversion);
    unset($pninit);
    unset($pntable);
    unset($pnadmin);
    unset($pnadminform);
    unset($pnuser);
    unset($pnuserform);
    unset($classes);
    unset($pntemplate);
    unset($plugins);
    unset($pninclude);

  }
?>

