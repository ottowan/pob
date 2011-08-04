<?php
function pn_exit($msg){
  exit($msg);
}
/**
* return resource   for db connection
*/
function &ypcore_opendb(){
  global $PNConfig;

  $is_encode = $PNConfig['DBInfo']['default']['encoded'];
  $dbhost = $PNConfig['DBInfo']['default']['dbhost'];
  $dbuser = $PNConfig['DBInfo']['default']['dbuname'];
  $dbpass = $PNConfig['DBInfo']['default']['dbpass'];
  $dbname = $PNConfig['DBInfo']['default']['dbname'];
  $charset = $PNConfig['DBInfo']['default']['dbcharset'];
  $dbtype = $PNConfig['DBInfo']['external1']['dbtype'];
  
  if ($dbtype != 'mysql'){
    die('ERROR: this system only support mysql db only.');
  }

  if($is_encode){
    $dbuser = base64_decode($dbuser);
    $dbpass = base64_decode($dbpass);
  }
  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysql_select_db($dbname);
  if($charset){
    mysql_query("SET NAMES " . $charset);
  }
  return $conn;
}

function ypcore_closedb(&$conn){
  if($conn != null){
    mysql_close($conn);
  }
}

function ypcore_getTable(){
  global $modversion;
  if(function_exists($modversion['name'] . '_pntables')){
    $tfunc = $modversion['name'] . '_pntables';
    return $tfunc();
  }
}

class YPDBUtil {
  var $conn = null;

  function __construct() {
    $this->conn = ypcore_opendb();
    
  }
  function __destruct() {
    ypcore_closedb($this->conn);
  }

  function ypselectObjectById($table,$id,$field='id'){
    $pntables = ypcore_getTable();
    $col = $pntables[$table . '_column'];
    
    $where = DBUtil::_checkWhereClause("{$col[$field]} = '$id'");
    return YPDBUtil::ypselectObject($table,$where);
  }

  function ypselectObject($table,$where=null){
    $pntables = ypcore_getTable();
    $where = DBUtil::_checkWhereClause($where);
    $col = $pntables[$table . '_column'];
    
    $sql = DBUtil::_getSelectAllColumnsFrom($table,$where);

    $result = mysql_query($sql);
    if($result){
      return mysql_fetch_assoc($result);
    }else{
      echo "ERROR: incorrect sql command.";
    }
  }


}//end class