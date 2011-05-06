<?php
  class PNSeason extends PNObject {
    function PNSeason($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_season';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>