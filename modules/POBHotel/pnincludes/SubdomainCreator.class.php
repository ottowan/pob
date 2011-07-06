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
  function makedb($dbname)
  {
    /*
    echo "DB Name : ".$dbname;
    echo "<BR>DB Username : ".$this->dbusername;
    echo "<BR>DB Password : ".$this->dbpassword;
    echo "<BR>DB Type : ".$this->dbtype;
    echo "<BR>DB Host : ".$this->dbhost;
    */
    $this->dbname = $dbname;
    
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
  
  public function sqlDump(){
    
    $backUpDB = $GLOBALS['PNConfig']['DBInfo']['default']['dbname'];
    
    $GLOBALS['PNConfig']['DBInfo']['default']['dbname'] = $this->dbname;
    pnDBInit();
    
    if($this->proceed){
      // create the database
      // set sql dump file path
      $fileurl = 'modules/POBHotel/data/zikulacms.sql';
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

    //$this->installmodules();
    $this->createuser($this->form['username'],$this->form['password'],$this->form['email']);
    $GLOBALS['PNConfig']['DBInfo']['default']['dbname'] = $backUpDB;
  }
function createuser($username, $password, $email)
{
    // get the database connection
    pnModDBInfoLoad('Users', 'Users');
    pnModDBInfoLoad('Modules', 'Modules');
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    // create the password hash
    $password = DataUtil::hash($password, pnModGetVar('Users', 'hash_method'));

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

function installmodules($installtype = 'basic', $lang = 'en')
{
    static $modscat;

    // Lang validation
    $lang = DataUtil::formatForOS($lang);

    // load our installation configuration
    $installtype = DataUtil::formatForOS($installtype);
    if ($installtype == 'complete') {
    } elseif (file_exists("modules/POBHotel/pnincludes/$installtype.php")) {
        include "modules/POBHotel/pnincludes/$installtype.php";
        $func = "installer_{$installtype}_modules";
        $modules = $func();
    } else {
        return false;
    }

    // create a result set
    $results = array();

    if ($installtype == 'basic') {
        $coremodules = array('Modules', 'Admin', 'Permissions', 'Groups', 'Blocks', 'ObjectData', 'Users', 'Theme', 'Settings');
        // manually install the modules module
        foreach ($coremodules as $coremodule) {
            // sanity check - check if module is already installed
            if ($coremodule != 'Modules' && pnModAvailable($coremodule)) {
                continue;
            }
            pnModDBInfoLoad($coremodule, $coremodule);
            Loader::requireOnce("system/$coremodule/pninit.php");
            $modfunc = "{$coremodule}_init";
            if ($modfunc()) {
                $results[$coremodule] = true;
            }
        }

        pnUserLogin('Admin', 'Password', false);
        // regenerate modules list
        $filemodules = pnModAPIFunc('Modules', 'admin', 'getfilemodules');
        pnModAPIFunc('Modules', 'admin', 'regenerate', array('filemodules' => $filemodules));

        // set each of the core modules to active
        reset($coremodules);
        foreach ($coremodules as $coremodule) {
            $mid = pnModGetIDFromName($coremodule, true);
            pnModAPIFunc('Modules', 'admin', 'setstate', array('id' => $mid, 'state' => PNMODULE_STATE_INACTIVE));
            pnModAPIFunc('Modules', 'admin', 'setstate', array('id' => $mid, 'state' => PNMODULE_STATE_ACTIVE));
        }
        // Add them to the appropriate category
        reset($coremodules);

        $coremodscat = array('Modules' => __('System'), 'Permissions' => __('Users'), 'Groups' => __('Users'), 'Blocks' => __('Layout'), 'ObjectData' => __('System'), 'Users' => __('Users'), 'Theme' => __('Layout'), 'Admin' => __('System'), 'Settings' => __('System'));

        $categories = pnModAPIFunc('Admin', 'admin', 'getall');
        $modscat = array();
        foreach ($categories as $category) {
            $modscat[$category['catname']] = $category['cid'];
        }
        foreach ($coremodules as $coremodule) {
            $category = $coremodscat[$coremodule];
            pnModAPIFunc('Admin', 'admin', 'addmodtocategory', array('module' => $coremodule, 'category' => $modscat[$category]));
        }
        // create the default blocks.
        Loader::requireOnce('system/Blocks/pninit.php');
        blocks_defaultdata();
    }

    if ($installtype == 'complete') {
        $modules = array();
        $mods = pnModAPIFunc('Modules', 'admin', 'list', array('state' => PNMODULE_STATE_UNINITIALISED));
        foreach ($mods as $mod) {
            if (!pnModAvailable($mod['name'])) {
                $modules[] = $mod['name'];
            }
        }
        foreach ($modules as $module) {
            ZLanguage::bindModuleDomain($module);

            $mid = pnModGetIDFromName($module);
            // No need to specify 'interactive_init' => false here because defined('_PNINSTALLVER') evals to true in modules_pnadminapi_initialise
            $initialise = pnModAPIFunc('Modules', 'admin', 'initialise', array('id' => $mid));
            if ($initialise === true) {
                // activate it
                if (pnModAPIFunc('Modules', 'admin', 'setstate', array('id' => $mid, 'state' => PNMODULE_STATE_ACTIVE))) {
                    $results[$module] = true;
                }
            } else if ($initialise === false) {
                $results[$module] = false;
            } else {
                unset($results[$module]);
            }
        }
    } else {
        foreach ($modules as $module) {
        	ZLanguage::bindModuleDomain($module);
            // sanity check - check if module is already installed
            if (pnModAvailable($module['module'])) {
                continue;
            }

            $results[$module['module']] = false;

            // #6048 - prevent trying to install modules which are contained in an install type, but are not available physically
            if (!file_exists('system/' . $module['module'] . '/') && !file_exists('modules/' . $module['module'] . '/')) {
                continue;
            }

            $mid = pnModGetIDFromName($module['module']);

            // init it
            if (pnModAPIFunc('Modules', 'admin', 'initialise', array('id' => $mid)) == true) {
                // activate it
                if (pnModAPIFunc('Modules', 'admin', 'setstate', array('id' => $mid, 'state' => PNMODULE_STATE_ACTIVE))) {
                    $results[$module['module']] = true;
                }
                // Set category
                pnModAPIFunc('Admin', 'admin', 'addmodtocategory', array('module' => $module['module'], 'category' => $modscat[$module['category']]));
            }
        }
    }
    pnConfigSetVar('language_i18n', $lang);

    // run any post-install routines
    $func = "installer_{$installtype}_post_install";
    if (function_exists($func)) {
        $func();
    }

    return $results;
}
  
}
?>