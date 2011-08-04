<?php
/**
* Utility extendstion 
* @package YellowPHP
* @subpackage pnincludes
* @author Chayakon PONGSIRI
*/
class InnoUtil {
  /**
  *
  */
  public static function createUrlFromArray($urlArray){
    //default value
    $modname = 'InnoForum';
    $func = 'list';
    $type = 'user';
    //
    $param = array();
    foreach($urlArray as $key => $value){
      if (strcmp(strtolower($key),'modname') === 0){
        $modname = $value;
      }else if (strcmp(strtolower($key),'func') === 0){
        $func = $value;
      }else if (strcmp(strtolower($key),'type') === 0){
        $type = $value;
      }else{
        $param[] = $key . '=' .$value;
      }
    }
    return "index.php?module=$modname&func=$func&type=$type&" . implode('&',$param);
  }
  /**
  * get setting data 
  * @example  in template 
  * $setting.MAP_API_KEY
  * 
  * @return array   the associate key 
  */
  public static function getSetting($table = SETTING_TABLE){
    $settingArray = DBUtil::selectObjectArray($table);
    $setting = array();
    foreach($settingArray as $item){
      $setting[$item['key']] = $item['value'];
    }
    unset($settingArray);
    return $setting;
  }
  /**
  * getSesecurityLevelByUserId to get security access level for current user login
  * @see pnSecurity.php
  * @return int the user infomation or false if guest
  * the user infomation
    [uid] => 2
    [uname] => admin
    [email] => test@test.com
    [user_regdate] => 2008-09-01 13:10:47
    [user_viewemail] => 0
    [user_theme] => 
    [pass] => XXX
    [storynum] => 10
    [ublockon] => 0
    [ublock] => 
    [theme] => 
    [counter] => 0
    [activated] => 1
    [lastlogin] => 2008-09-01 13:10:47
    [validfrom] => 0
    [validuntil] => 0
    [hash_method] => 8
    [pn_uid] => 2
    [pn_uname] => admin
    [pn_email] => test@test.com
    [pn_user_regdate] => 2008-09-01 13:10:47
    [pn_user_viewemail] => 0
    [pn_user_theme] => 
    [pn_pass] => XXX
    [pn_storynum] => 10
    [pn_ublockon] => 0
    [pn_ublock] => 
    [pn_theme] => 
    [pn_counter] => 0
    [pn_activated] => 1
    [pn_lastlogin] => 2008-09-01 13:10:47
    [level] = > XXX (range 0 - 800)
  */
  public static function getUserInfo($uid = null){
    if (empty($uid)){
      $uid = pnUserGetVar('uid');
    }
    $uinfo = pnUserGetVars($uid);

    //keep old value
    $old_authinfogathered = $GLOBALS['authinfogathered'];
    $uauth = SecurityUtil::getAuthInfo($uid);
    //restore value
    $GLOBALS['authinfogathered'] = $old_authinfogathered;
    
    $ulevel = SecurityUtil::getSecurityLevel($uauth);
    $uinfo['level'] = $ulevel;
    if ($uid){
      Loader::loadClass('UserUtil');
      $dud = UserUtil::getUserDynamicDataFields($uid,'uda_propid', true);
      return $uinfo;
    }
    return false;
  }
  /**
  * getServerPath get the server referer path 
  * NOTE becare full use this function , because some web server will not set $_SERVER['HTTP_REFERER']
  * @example if the refer is http://localhost/klonghae3d/index.php?module=YellowPHP&type=admin&func=list&ctrl=Business
  *          it will return klonghae3d
  *          InnoUtil::getServerPath('/','')
  * @param  $prefix 
  * @param  $postfix 
  * @return string  
  */
  public static function getServerPath($prefix = '',$postfix = ''){
    $refer = $_SERVER['HTTP_REFERER'];
    //$refer = "http://localhost/klonghae3d/index.php?module=YellowPHP&type=admin&func=list&ctrl=Business";
    $pathArray = explode('/',$refer);
    $path = '';
    if (count($pathArray) >= 4){
      for($i = 3; $i < count($pathArray) - 1; $i++){
        $path .= $pathArray[$i] . '/';
      }
      if (!empty($path)){
        $path = $prefix . substr($path,0,strlen($path) - 1) . $postfix;
      }
    }
    return $path;
  }
  /**
  * get client ip address
  */
  public static function getIpAddress() {
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
      $result = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
      $result = $_SERVER['HTTP_CLIENT_IP'];
    } else {
      $result = $_SERVER['REMOTE_ADDR'];
    }
    if (strstr($result, ',')) {
      $result = strtok($result, ',');
    }
    return $result;
  }

  /**
  * get page type 
  * @return string  home | module | admin
  */
  public static function getPageType(){
    $module = FormUtil::getPassedValue('module', null, 'GETPOST');
    $pagetype = 'module';
    $type   = FormUtil::getPassedValue('type', null, 'GETPOST');
    if ((stristr($_SERVER['PHP_SELF'], 'admin.php') || strtolower($type) == 'admin')) {
        $pagetype = 'admin';
    } else if (empty($name) && empty($module)) {
        $pagetype = 'home';
    }
    return $pagetype;
  }

  function getBaseURL(){
      $server = pnServerGetVar('SERVER_NAME');
      // IIS sets HTTPS=off
      $https = pnServerGetVar('HTTPS', 'off');
      if ($https != 'off') {
          $proto = 'https://';
      } else {
          $proto = 'http://';
      }

      $path = pnGetBaseURI();

      return "$proto$server$path/";
  }

}