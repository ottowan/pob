<?php
  class PNLocationCategory extends PNObject {
    function PNLocationCategory($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_location_category';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>