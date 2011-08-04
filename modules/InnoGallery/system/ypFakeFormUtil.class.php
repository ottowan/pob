<?php
class FormUtil{
  public static function getPassedValue($key, $default=null, $source=null){
    if (!$key) {
        die ('Empty key passed to FormUtil::getPassedValueSafe() ...');
    }
    $source = strtoupper($source);
    $src    = ($source ? $source : 'REQUEST') . '_' . ($default != null ? $default : 'null');
    $doClean = false;
    switch (true)
    {
        case (isset($_REQUEST[$key]) && !isset($_FILES[$key]) && (!$source || $source=='R' || $source=='REQUEST')):
            $value = $_REQUEST[$key];
            $doClean = true;
            break;
        case isset($_GET[$key]) && (!$source || $source=='G' || $source=='GET'):
            $value = $_GET[$key];
            $doClean = true;
            break;
        case isset($_POST[$key]) && (!$source || $source=='P' || $source=='POST'):
            $value = $_POST[$key];
            $doClean = true;
            break;
        case isset($_COOKIE[$key]) && (!$source || $source=='C' || $source=='COOKIE'):
            $value = $_COOKIE[$key];
            $doClean = true;
            break;
        case isset($_FILES[$key]) && ($source=='F' || $source=='FILES'):
            $value = $_FILES[$key];
            break;
        case (isset($_GET[$key]) || isset($_POST[$key])) && ($source=='GP' || $source=='GETPOST'):
            if (isset($_GET[$key])) {
                $value = $_GET[$key];
            }
            if (isset($_POST[$key])) {
                $value = $_POST[$key];
            }
            $doClean = true;
            break;
        default:
            if ($source) {
                static $valid = array ('R', 'REQUEST', 'G', 'GET', 'P', 'POST', 'C', 'COOKIE', 'F', 'FILES', 'GP', 'GETPOST');
                if (!in_array($source, $valid)) {
                    die ('Invalid input source [' . $source . '] received ...');
                }
            }
    }//end case
    if (isset($value) && !is_null($value))
    {
      return $value;
    }
    return $default;
  }
}