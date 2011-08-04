<?php
/**
* read csv file store in array 
* mapping key to first line that start with #
* @author Chayakon PONGSIRI
*
*<b>NOTE*</B> required iconv extendstion install
*
*
* @param  file  string file name with full path
* @return array of data . 0 | false when error
* <code>
* $reader = new CSVReader();
* $reader->readcsv('file name with full path');
* </code>
* the result are follow
* $result = array( 0 = > array( 'id' => 1 , 'name' => 'myname1'),
*                  1 = > array( 'id' => 2 , 'name' => 'myname2'),.....
);
*/

class CSVReader{
  function readheader($file){
    $handle = fopen($file, "r");
    $data = null;
    if (!empty($handle)){
      $data = CSVReader::fgetcsv_ex($handle, 1000, ",");
      if (strpos($data[0],'#') !== FALSE){
        $data[0] = str_replace('#','',$data[0]);
      }
    }
    fclose($handle);
    $output = array();
    foreach($data as $value){
      $output[] = strtolower(trim($value));
    }
    unset($data);
    return $output;
  }
  /*
  * 
  */
  function readcsv($file,$limit=-1,$fieldArray=false,&$error_msg=''){
    //build header for intersection with $fieldArray
    if (is_array($fieldArray)){
      $fieldHeader = array_values($fieldArray);
      $fieldArray = array_combine($fieldHeader,$fieldArray);
    }
    $handle = fopen($file, "r");
    //check ,is file can handle
    if (!empty($handle)){
      
      $objArray =array();
      $header = array();
      $row = 0;

      $firstline = CSVReader::fgetcsv_ex($handle, 1000, ",");
      //get header and remove # sign
      //exit if no # found in firstline
      if (strpos($firstline[0],'#') !== FALSE){
        $firstline[0] = str_replace('#','',$firstline[0]);
        $header = array();
        foreach($firstline as $value){
          $header[] = strtolower(trim($value));
        }
      }else{
        $error_msg = 'ERROR: first line not start with #';
        return false;
      }
      while (($data = CSVReader::fgetcsv_ex($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          $row++;
          $sum = array();
          //convert character encoding to international UTF-8
          for ($i = 0 ; $i < count($data); $i++){
            $data[$i] = iconv("windows-874", "UTF-8",trim($data[$i]));
            $sum[] = strlen($data[$i]);
          }
          //check and skip empty row
          if (array_sum($sum) <=0){
            continue;
          }
          //filter value only exist in $fieldArray else no filter
          if (!empty($num)){
            if (is_array($fieldArray)){
              $row = array_combine($header,$data);
              $objArray[] = CSVReader::array_key_intersect($row,$fieldArray);
            }else{
              $objArray[] = array_combine($header,$data);  
            }
            
          }
          //check limit line if $limit is set
          if ($row >= $limit && $limit > 0){
            break;
          }
      }
      fclose($handle);
      return $objArray;
    }else{
      return false;
    }
  }
  function array_key_intersect(&$a, &$b) {
    $array = array();
    while (list($key,$value) = each($a)) {
      if (isset($b[$key]))
        $array[$key] = $value;
    }
    return $array;
  }
  /*
  * get csv data line by line with multibyte fix
  */
  function fgetcsv_ex($handle,$buffer = 1024, $seperate = ','){
    if ($handle && !feof($handle)){
      $data = fgets($handle);
      return CSVReader::mb_csv_split($data,$seperate);
    }
    return false;
  }
  /**
 * @param the csv line to be split
 * @param the delimiter to split by (default ';' )
 * @param if this is false, the quotation marks won't be removed from the fields (default true)
 */
  function mb_csv_split($line, $delim = ',', $removeQuotes = true) {
      $fields = array();
      $fldCount = 0;
      $inQuotes = false;

      for ($i = 0; $i < mb_strlen($line); $i++) {
          if (!isset($fields[$fldCount])) $fields[$fldCount] = "";
          $tmp = mb_substr($line, $i, mb_strlen($delim));
          if ($tmp === $delim && !$inQuotes) {
              $fldCount++;
              $i+= mb_strlen($delim) - 1;
          } 
          else if ($fields[$fldCount] == "" && mb_substr($line, $i, 1) == '"' && !$inQuotes) {
              if (!$removeQuotes) $fields[$fldCount] .= mb_substr($line, $i, 1);
              $inQuotes = true;
          } 
          else if (mb_substr($line, $i, 1) == '"') {
              if (mb_substr($line, $i+1, 1) == '"') {
                  $i++;
                  $fields[$fldCount] .= mb_substr($line, $i, 1);
              } else {
                  if (!$removeQuotes) $fields[$fldCount] .= mb_substr($line, $i, 1);
                  $inQuotes = false;
              }
          }
          else {
              $fields[$fldCount] .= mb_substr($line, $i, 1);
          }
      }
      return $fields;
  }
}