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
    $host = "http://".$host.'/index.php';
    
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
    switch ($func) {
      case  'getHotelList':
            $paramUri = '';
            foreach ($params AS $key=>$val){
              $paramUri .= "&".$key."=".$val;
            }
            $url = $this->host."?module=".$this->_MOD."&type=ws&func=".$func.$paramUri;

            $xml = @file_get_contents($url);
            $pxml = simplexml_load_string($xml);
            
            $pxml = $this->_toArray($pxml);

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
            
            $pxml = $this->_toArray($pxml);
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
            
            $pxml = $this->_toArray($pxml);
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
  
  private function _toArray($obj, $level=0) {
      
      $items = array();
      
      if(!is_object($obj)) return $items;
          
      $child = (array)$obj;

      if(sizeof($child)>1) {
          foreach($child as $aa=>$bb) {
              if(is_array($bb)) {
                  foreach($bb as $ee=>$ff) {
                      if(!is_object($ff)) {
                          $items[$aa][$ee] = $ff;
                      } else
                      if(get_class($ff)=='SimpleXMLElement') {
                          $items[$aa][$ee] = $this->_toArray($ff,$level+1);
                      }
                  }
              } else
              if(!is_object($bb)) {
                  $items[$aa] = $bb;
              } else
              if(get_class($bb)=='SimpleXMLElement') {
                  $items[$aa] = $this->_toArray($bb,$level+1);
              }
          }
      } else
      if(sizeof($child)>0) {
          foreach($child as $aa=>$bb) {
              if(!is_array($bb)&&!is_object($bb)) {
                  $items[$aa] = $bb;
              } else
              if(is_object($bb)) {
                  $items[$aa] = $this->_toArray($bb,$level+1);
              } else {
                  foreach($bb as $cc=>$dd) {
                      if(!is_object($dd)) {
                          $items[$obj->getName()][$cc] = $dd;
                      } else
                      if(get_class($dd)=='SimpleXMLElement') {
                          $items[$obj->getName()][$cc] = $this->_toArray($dd,$level+1);
                      }
                  }
              }
          }
      }
      return $items;
  }
  public function getErrorMessage(){
    return $this->ERROR;
  }
}
?>