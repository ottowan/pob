<?php
function POBHotel_test_getContent(){
    $moduleName = "POBHotel";

  if($moduleName!=''){
      if(file_exists("modules/$moduleName")){
      $sql = "insert  into `z_modules`(`pn_name`,`pn_type`,`pn_displayname`,`pn_url`,`pn_description`,`pn_regid`,`pn_directory`,`pn_version`,`pn_official`,`pn_author`,`pn_contact`,`pn_admin_capable`,`pn_user_capable`,`pn_profile_capable`,`pn_message_capable`,`pn_state`,`pn_credits`,`pn_changelog`,`pn_help`,`pn_license`,`pn_securityschema`)
    values
    ('Modules',2,'Modules','Modules','Modules API.',0,'Modules','1.0',1,'Thapakorn Tantirattanapong','http://www.phuketinnova.com',1,1,0,0,1,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:0:{}')";
    
      $sql = str_replace("Modules",$moduleName,$sql);  
      $sql = str_replace('z_', '_', $sql);
      DBUtil::executeSQL($sql);
  
    }
  }
  pnShutDown();
}

?>