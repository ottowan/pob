<?php
/**
getHotelList($params)
getRoomListByHotelId($params)
searchHotel($params)
*/

class POBReader {

  private $host='';
  private $_MOD = '';
  private $ERROR = '';
  
  function __construct($host=NULL,$module=NULL){
    if(is_null($host)){
      $host = "http://www.phuketcity.com/";
    }
    if(is_null($module)){
      $module = 'POBHotel';
    }
    
    //Prepare domain
    preg_match('@^(?:http://)?([^/].*)@i',$host,$matches);
    preg_match('@^(?:www.)?([^/].*)@i',$matches[1],$host);
    $host = rtrim($host[1],"/");
    $host = "http://www.".$host.'/index.php';
    
    $this->host = $host;
    $this->_MOD = $module;
  }

  public function getHotelList($params=NULL){
    return $this->_call('getHotelList',$params);
  }
  
  public function getRoomListByHotelId($params=NULL){
    return $this->_call('getRoomListByHotelId',$params);
  }
    
  public function searchHotel($params=NULL){
    return $this->_call('searchHotel',$params);
  }

  
  private function _call($func='',$params=''){
    $pxml = array();
    switch ($func) {
      case  'getHotelList':
            $paramUri = '';
            foreach ($params AS $key=>$val){
              $paramUri .= "&".$key."=".$val;
            }
            $url = $this->host."?module=".$this->_MOD."&type=ws&func=".$func.$paramUri;
            
            $xml = @file_get_contents($url);
            $pxml = simplexml_load_string($xml);
            
            $pxml = $this->xmlToArray($pxml);

            if($pxml['data']['title']=='ไม่มีข้อมูล'){
              $this->ERROR = "NO RECORD";
              return FALSE;
            }
            if(is_null($pxml['datas'])){
              return $pxml;
            }else{
              return $pxml['datas'];
            }
            break;
      case 'getRoomListByHotelId':
            $paramUri = '';
            foreach ($params AS $key=>$val){
              $paramUri .= "&".$key."=".$val;
            }
            $url = $this->host."?module=".$this->_MOD."&type=ws&func=".$func.$paramUri;

            $xml = @file_get_contents($url);
            $pxml = simplexml_load_string($xml);
            
            $pxml = $this->xmlToArray($pxml);
            if(is_null($pxml['datas'])){
              return $pxml;
            }else{
              return $pxml['datas'];
            }
            break;
      case 'searchHotel':
            $paramUri = '';
            foreach ($params AS $key=>$val){
              $paramUri .= "&".$key."=".$val;
            }
            $url = $this->host."?module=".$this->_MOD."&type=ws&func=".$func.$paramUri;

            $xml = @file_get_contents($url);
            $pxml = simplexml_load_string($xml);
            
            $pxml = $this->xmlToArray($pxml);
            if(is_null($pxml['datas'])){
              return $pxml;
            }else{
              return $pxml['datas'];
            }
            break;
      default :
            return FALSE;
    }
  }
  

  ////////////////////////////////
  //xmlToArray
  ///////////////////////////////

  public function xmlToArray($obj, $level=0) {
     
      $items = array();

      //var_dump($obj); exit;
      
      if(!is_object($obj)){
        $obj = simplexml_load_string($obj);
        if(!is_object($obj)){
           return "Send wrong type parameter to function xmlToArray()";
        }
      }
          
      $child = (array)$obj;
      if(sizeof($child)>1) {
          foreach($child as $aa=>$bb) {
              if(is_array($bb)) {
                  foreach($bb as $ee=>$ff) {
                      if(!is_object($ff)) {
                          $items[$aa][$ee] = $ff;
                      } else
                      if(get_class($ff)=='SimpleXMLElement') {
                          $items[$aa][$ee] = $this->xmlToArray($ff,$level+1);
                      }
                  }
              } else
              if(!is_object($bb)) {
                  $items[$aa] = $bb;
              } else
              if(get_class($bb)=='SimpleXMLElement') {
                  $items[$aa] = $this->xmlToArray($bb,$level+1);
              }
          }
      } else
      if(sizeof($child)>0) {
          foreach($child as $aa=>$bb) {
              if(!is_array($bb)&&!is_object($bb)) {
                  $items[$aa] = $bb;
              } else
              if(is_object($bb)) {
                  $items[$aa] = $this->xmlToArray($bb,$level+1);
              } else {
                  foreach($bb as $cc=>$dd) {
                      if(!is_object($dd)) {
                          $items[$obj->getName()][$cc] = $dd;
                      } else
                      if(get_class($dd)=='SimpleXMLElement') {
                          $items[$obj->getName()][$cc] = $this->xmlToArray($dd,$level+1);
                      }
                  }
              }
          }
      //var_dump($items);
      }


      return $items;
  }

  /** 
   * xml2array() will convert the given XML text to an array in the XML structure. 
   * Link: http://www.bin-co.com/php/scripts/xml2array/ 
   * Arguments : $contents - The XML text 
   *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
   *                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
   * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure. 
   * Examples: $array =  xml2array(file_get_contents('feed.xml')); 
   *              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute')); 
   */ 
  function xml2arrayV2($contents, $get_attributes=1, $priority = 'tag') { 
      if(!$contents) return array(); 

      if(!function_exists('xml_parser_create')) { 
          //print "'xml_parser_create()' function not found!"; 
          return array(); 
      } 

      //Get the XML parser of PHP - PHP must have this module for the parser to work 
      $parser = xml_parser_create(''); 
      xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss 
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
      xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
      xml_parse_into_struct($parser, trim($contents), $xml_values); 
      xml_parser_free($parser); 

      if(!$xml_values) return;//Hmm... 

      //Initializations 
      $xml_array = array(); 
      $parents = array(); 
      $opened_tags = array(); 
      $arr = array(); 

      $current = &$xml_array; //Refference 

      //Go through the tags. 
      $repeated_tag_index = array();//Multiple tags with same name will be turned into an array 
      foreach($xml_values as $data) { 
          unset($attributes,$value);//Remove existing values, or there will be trouble 

          //This command will extract these variables into the foreach scope 
          // tag(string), type(string), level(int), attributes(array). 
          extract($data);//We could use the array by itself, but this cooler. 

          $result = array(); 
          $attributes_data = array(); 
           
          if(isset($value)) { 
              if($priority == 'tag') $result = $value; 
              else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode 
          } 

          //Set the attributes too. 
          if(isset($attributes) and $get_attributes) { 
              foreach($attributes as $attr => $val) { 
                  if($priority == 'tag') $attributes_data[$attr] = $val; 
                  else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
              } 
          } 

          //See tag status and do the needed. 
          if($type == "open") {//The starting of the tag '<tag>' 
              $parent[$level-1] = &$current; 
              if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
                  $current[$tag] = $result; 
                  if($attributes_data) $current[$tag. '_attr'] = $attributes_data; 
                  $repeated_tag_index[$tag.'_'.$level] = 1; 

                  $current = &$current[$tag]; 

              } else { //There was another element with the same tag name 

                  if(isset($current[$tag][0])) {//If there is a 0th element it is already an array 
                      $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
                      $repeated_tag_index[$tag.'_'.$level]++; 
                  } else {//This section will make the value an array if multiple tags with the same name appear together 
                      $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                      $repeated_tag_index[$tag.'_'.$level] = 2; 
                       
                      if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well 
                          $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
                          unset($current[$tag.'_attr']); 
                      } 

                  } 
                  $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
                  $current = &$current[$tag][$last_item_index]; 
              } 

          } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
              //See if the key is already taken. 
              if(!isset($current[$tag])) { //New Key 
                  $current[$tag] = $result; 
                  $repeated_tag_index[$tag.'_'.$level] = 1; 
                  if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data; 

              } else { //If taken, put all things inside a list(array) 
                  if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array... 

                      // ...push the new element into that array. 
                      $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
                       
                      if($priority == 'tag' and $get_attributes and $attributes_data) { 
                          $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
                      } 
                      $repeated_tag_index[$tag.'_'.$level]++; 

                  } else { //If it is not an array... 
                      $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value 
                      $repeated_tag_index[$tag.'_'.$level] = 1; 
                      if($priority == 'tag' and $get_attributes) { 
                          if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well 
                               
                              $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
                              unset($current[$tag.'_attr']); 
                          } 
                           
                          if($attributes_data) { 
                              $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
                          } 
                      } 
                      $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken 
                  } 
              } 

          } elseif($type == 'close') { //End of tag '</tag>' 
              $current = &$parent[$level-1]; 
          } 
      } 
       
      return($xml_array); 
  } 

  public function getErrorMessage(){
    return $this->ERROR;
  }
}
?>