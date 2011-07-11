<?php
  /**
  * 
  * 
  * 
  */
Class SubdomainCreator {
  private $proceed = NULL;
  private $dbusername = NULL;
  private $dbpassword = NULL;
  private $dbtype = NULL;
  private $prefix = NULL;
  private $dbname = '';
  private $username;
  private $password;
  private $email;
  private $form;
  private $exist;
  private $_error;
  
  function __construct($dbpassword=NULL, $dbusername=NULL, $dbtype=NULL, $dbhost=NULL){
    $this->dbusername = $dbusername;
    $this->dbpassword = $dbpassword;
    $this->dbtype = $dbtype;
    $this->dbhost = $dbhost;
    $this->prefix = $GLOBALS['PNConfig']['System']['prefix'];
    
    if(is_null($this->dbpassword)){
      $this->dbpassword = $GLOBALS['PNConfig']['DBInfo']['default']['dbpass'];
    }
    if(is_null($this->dbusername)){
      $this->dbusername = $GLOBALS['PNConfig']['DBInfo']['default']['dbuname'];
    }
    if(is_null($this->dbtype)){
      $this->dbtype = $GLOBALS['PNConfig']['DBInfo']['default']['dbtype'];
    }
    if(is_null($this->dbhost)){
      $this->dbhost = $GLOBALS['PNConfig']['DBInfo']['default']['dbhost'];
    }
    $this->form = FormUtil::getPassedValue('form', null, 'REQUEST');

  }
  
    /**
   * Creates the DB on new install
   *
   * This function creates the DB on new installs
   *
   * @param string $dbconn Database connection
   * @param string $dbname Database name
   */
  function makedb($dbname,$username,$password,$email)
  {
    
    echo "DB Name : ".$dbname;
    echo "<BR>DB Username : ".$this->dbusername;
    echo "<BR>DB Password : ".$this->dbpassword;
    echo "<BR>DB Type : ".$this->dbtype;
    echo "<BR>DB Host : ".$this->dbhost;
    
    $this->dbname = $dbname;
    $this->username = $username;
    $this->password = $password;
    $this->email = $email;
    //function makedb($dbtype, $dbhost, $dbusername, $dbpassword, $dbname, $charset, $collation) {
    // make a new database - the adodb way
    $dbconn = ADONewConnection($this->dbtype);
    // note adodb's use of mysql_connect returns a warning so checking
    // $dbh afterwards won't prevent a warning being displayed
    // so for ease we suppress errors here
    $dbh = @$dbconn->NConnect($this->dbhost, $this->dbusername, $this->dbpassword);
    if (!$dbh) {
        return false;
    }

    $databaseExists = $dbconn->Execute("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$dbname."'");
    $databaseExists = DBUtil::marshallObjects($databaseExists, array('exist'));
    //var_dump(!isset($databaseExists[0]));
    //exit;
    if(!isset($databaseExists[0])){
    $this->exist = 0;
      $sql = array();
      if ($this->dbtype == 'mysql' || $this->dbtype == 'mysqli') {
          $sql[] = "CREATE DATABASE `$dbname` DEFAULT CHARACTER SET utf8";
      } else if ($this->dbtype == 'postgres') {
          $sql[] = "CREATE DATABASE $dbname ENCODING='utf8'";
      } else if ($this->dbtype == 'oci') {
          $sql[] = "CREATE DATABASE $dbname national character set utf8";
      } else {
          $sql = false;
      }
  
      $dict = NewDataDictionary($dbconn);
      $sql = (!$sql ? $dict->CreateDatabase($dbname) : $sql);
      $return = $dict->ExecuteSQLArray($sql);
      
      if($return!=FALSE){
        $this->proceed = TRUE;
      }
      
      return $return;
    }
    $this->_error = "Database is exist!";
    $this->exist = 1;
    echo "<br>Make Db : ".$this->_error;
    return FALSE;
  }
  
  public function sqlDump(){
    
    if(!$this->exist){
      $backUpDB = $GLOBALS['PNConfig']['DBInfo']['default']['dbname'];
    
      $GLOBALS['PNConfig']['DBInfo']['default']['dbname'] = $this->dbname;
      pnDBInit();
      
      if($this->proceed){
        // create the database
        // set sql dump file path
        $fileurl = 'modules/POBMember/data/zikulacms.sql';
        //var_dump(file_exists($fileurl));
        // checks if file exists
        if (!file_exists($fileurl)) {
            //do something
        } else {
          // execute the SQL dump
          $installed = true;
          $lines = file($fileurl);
          $exec = '';
          foreach ($lines as $line_num => $line) {
              $line = trim($line);
              if (empty($line) || strpos($line, '--') === 0)
                  continue;
              $exec .= $line;
              if (strrpos($line, ';') === strlen($line) - 1) {
                  if (!DBUtil::executeSQL(str_replace('z_', $this->prefix. '_', $exec))) {
                      $installed = false;
                      $action = 'dbinformation';
                      $smarty->assign('dbdumpfailed', true);
                      break;
                  }
                  $exec = '';
              }
            }
        }
      }
      /*
      $moduleName ='POBHotel';
      $sql = "SELECT count(pn_id) FROM ".$this->prefix."_modules WHERE pn_name LIKE '".$moduleName."'" ;
      $sql = DBUtil::executeSQL($sql);
      $sql = DBUtil::marshallObjects($sql, array('count'));

      if($sql[0]['count']==0){
        if($this->installmodules($moduleName)){
          echo "<BR>Install [$moduleName] Complete.";
        }else{
          echo "<BR>Install [$moduleName] failed.";
        }
      }else{
         echo "<BR>Module [$moduleName] is exists.";
      }
      
      */
      $this->createuser($this->username,$this->password,$this->email);
      $GLOBALS['PNConfig']['DBInfo']['default']['dbname'] = $backUpDB;
    }
    echo "<br>SQL Dump :".$this->_error;
    return FALSE;

  }
  function createuser($username, $password, $email)
  {
      // get the database connection
      pnModDBInfoLoad('Users', 'Users');
      pnModDBInfoLoad('Modules', 'Modules');
      $dbconn = pnDBGetConn(true);
      $pntable = pnDBGetTables();
  
      // create the password hash
      //$password = DataUtil::hash($password, pnModGetVar('Users', 'hash_method'));
  
      // prepare the data
      $username = DataUtil::formatForStore($username);
      $password = DataUtil::formatForStore($password);
      $email = DataUtil::formatForStore($email);
  
      // create the admin user
      $sql = "UPDATE $pntable[users]
              SET    pn_uname        = '$username',
                     pn_email        = '$email',
                     pn_pass         = '$password',
                     pn_activated    = '1',
                     pn_user_regdate = '" . date("Y-m-d H:i:s", time()) . "',
                     pn_lastlogin    = '" . date("Y-m-d H:i:s", time()) . "'
              WHERE  pn_uid   = 2";
  
      $result = $dbconn->Execute($sql);
  	
      return ($result) ? true : false;
  }
  
  function installmodules($moduleName=''){
  
    if($moduleName!=''){
        if(file_exists("modules/$moduleName")){
        $sql = "insert  into `z_modules`(`pn_name`,`pn_type`,`pn_displayname`,`pn_url`,`pn_description`,`pn_regid`,`pn_directory`,`pn_version`,`pn_official`,`pn_author`,`pn_contact`,`pn_admin_capable`,`pn_user_capable`,`pn_profile_capable`,`pn_message_capable`,`pn_state`,`pn_credits`,`pn_changelog`,`pn_help`,`pn_license`,`pn_securityschema`)
      values
      ('Modules',2,'Modules','Modules','Modules API.',0,'Modules','1.0',1,'Thapakorn Tantirattanapong','http://www.phuketinnova.com',1,1,0,0,1,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:0:{}')";
      
        $sql = str_replace("Modules",$moduleName,$sql);  
        $sql = str_replace('z_', $this->prefix. '_', $sql);
        //echo $sql."<BR>";
        DBUtil::executeSQL($sql);
        $sql = "SELECT pn_id FROM ".$this->prefix."_modules WHERE pn_name LIKE '".$moduleName."'" ;
        $sql = DBUtil::executeSQL($sql);
        $sql = DBUtil::marshallObjects($sql, array('pn_id'));

        if(isset($sql[0]["pn_id"])){
          $this->modules_initialise($sql[0]["pn_id"],true);
        }
        
        return true;
      }
    }
    return FALSE;
  }
  function modules_initialise($id,$confirmation)
  {
      // Get parameters from whatever input we need
      //$id = (int) FormUtil::getPassedValue('id', 0);
      //$objectid = (int) FormUtil::getPassedValue('objectid', 0);
      //$confirmation = (bool) FormUtil::getPassedValue('confirmation', false);
      //$startnum = (int) FormUtil::getPassedValue('startnum');
      //$letter = FormUtil::getPassedValue('letter');
      $state = FormUtil::getPassedValue('state');
      if ($objectid) {
          $id = $objectid;
      }
  
      // assign any dependencies - filtering out non-active module dependents
      // when getting here without a valid id we are in interactive init mode and then
      // the dependencies checks have been done before already
      if ($id != 0) {
          $dependencies = pnModAPIFunc('Modules', 'admin', 'getdependencies', array(
              'modid' => $id));
          $modulenotfound = false;
          if (empty($confirmation) && $dependencies) {
              foreach ($dependencies as $key => $dependency) {
                  $dependencies[$key]['insystem'] = true;
                  $modinfo = pnModGetInfo(pnModGetIDFromName($dependency['modname']));
                  if (pnModAvailable($dependency['modname'])) {
                      unset($dependencies[$key]);
                  } elseif (!empty($modinfo)) {
                      $dependencies[$key] = array_merge($dependencies[$key], $modinfo);
                  } else {
                      $dependencies[$key]['insystem'] = false;
                      $modulenotfound = true;
                  }
              }
  
              // we have some dependencies so let's warn the user about these
              if (!empty($dependencies)) {
                  $pnRender = & pnRender::getInstance('Modules', false);
                  $pnRender->assign('id', $id);
                  $pnRender->assign('dependencies', $dependencies);
                  $pnRender->assign('modulenotfound', $modulenotfound);
                  return $pnRender->fetch('modules_admin_initialise.htm');
              }
          } else {
              $dependencies = (array) FormUtil::getPassedValue('dependencies', array(), 'POST');
          }
      }
  
      $interactive_init = SessionUtil::getVar('interactive_init');
      $interactive_init = (empty($interactive_init)) ? false : true;
      if ($interactive_init == false) {
          SessionUtil::setVar('modules_id', $id);
          SessionUtil::setVar('modules_startnum', $startnum);
          SessionUtil::setVar('modules_letter', $letter);
          SessionUtil::setVar('modules_state', $state);
          $activate = false;
      } else {
          $id = SessionUtil::getVar('modules_id');
          $startnum = SessionUtil::getVar('modules_startnum');
          $letter = SessionUtil::getVar('modules_letter');
          $state = SessionUtil::getVar('modules_state');
          $activate = (bool) FormUtil::getPassedValue('activate');
      }
  
      if (empty($id) || !is_numeric($id)) {
          return LogUtil::registerError(__('Error! No module ID provided.'), 404, pnModURL('Modules', 'admin', 'view'));
      }
  
      // initialise and activate any dependencies
      if (isset($dependencies) && is_array($dependencies)) {
          foreach ($dependencies as $dependency) {
              if (!pnModAPIFunc('Modules', 'admin', 'initialise', array(
                  'id' => $dependency))) {
                  return pnRedirect(pnModURL('Modules', 'admin', 'view', array(
                      'startnum' => $startnum,
                      'letter' => $letter,
                      'state' => $state)));
              }
              if (!pnModAPIFunc('Modules', 'admin', 'setstate', array(
                  'id' => $dependency,
                  'state' => PNMODULE_STATE_ACTIVE))) {
                  return pnRedirect(pnModURL('Modules', 'admin', 'view', array(
                      'startnum' => $startnum,
                      'letter' => $letter,
                      'state' => $state)));
              }
          }
      }
  
      // Now we've initialised the dependencies initialise the main module
      $res = pnModAPIFunc('Modules', 'admin', 'initialise', array(
          'id' => $id,
          'interactive_init' => $interactive_init));
      if (is_bool($res) && $res == true) {
          // Success
          SessionUtil::delVar('modules_id');
          SessionUtil::delVar('modules_startnum');
          SessionUtil::delVar('modules_letter');
          SessionUtil::delVar('modules_state');
          SessionUtil::delVar('interactive_init');
          LogUtil::registerStatus(__('Done! Installed module.'));
  
          if ($activate == true) {
              if (pnModAPIFunc('Modules', 'admin', 'setstate', array(
                  'id' => $id,
                  'state' => PNMODULE_STATE_ACTIVE))) {
                  // Success
                  LogUtil::registerStatus(__('Done! Activated module.'));
              }
          }
          return pnRedirect(pnModURL('Modules', 'admin', 'view', array(
              'startnum' => $startnum,
              'letter' => $letter,
              'state' => $state)));
      } elseif (is_bool($res)) {
          return pnRedirect(pnModURL('Modules', 'admin', 'view', array(
              'startnum' => $startnum,
              'letter' => $letter,
              'state' => $state)));
      } else {
          return $res;
      }
  }
}
?>