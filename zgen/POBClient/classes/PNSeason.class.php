<?php
  class PNSeason extends PNObject {
    function PNSeason($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_season';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
    
  }
?>