<?php
  class PNHotelLocation extends PNObject {
    function PNHotelLocation($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel_location';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>