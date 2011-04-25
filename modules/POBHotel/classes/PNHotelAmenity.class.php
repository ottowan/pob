<?php
  class PNHotelAmenity extends PNObject {
    function PNHotelAmenity($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel_amenity';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>