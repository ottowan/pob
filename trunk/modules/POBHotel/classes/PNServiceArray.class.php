<?php
  class PNAmenityArray extends PNObjectArray {
    function PNAmenityArray($init=null, $where='') {
      $this->PNObject();
      $this->_objType       = 'pobhotel_amenity';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    }

    function genSort(){
      $order = ' ORDER BY amenity_id ASC';
     return $order;
    }
  }

?>