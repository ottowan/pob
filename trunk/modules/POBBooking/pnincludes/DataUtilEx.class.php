<?php 
class DataUtilEx {
  /**
  * fill string 
  * @example
  * echo DataUtilEx::fillString('123' , 5 , '0' , 'FX');
  * //result is FX00123
  */
  public static function fillString($value , $charlen , $fill = '0',$prefix = ''){
    $cur_len = strlen((string)$value);
    $fill = (string)$fill;
    $result = '';
    for($i = 0; $i < $charlen - $cur_len ; $i += strlen($fill)){
      $result .= $fill;
    }
    return $prefix . $result . $value;
  }
}