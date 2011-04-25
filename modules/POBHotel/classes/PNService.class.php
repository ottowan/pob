<?php
  class PNAmenity extends PNObject {
    function PNAmenity($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_amenity';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>