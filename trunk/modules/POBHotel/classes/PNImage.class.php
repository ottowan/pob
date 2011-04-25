<?php
  class PNHotelImage extends PNObject {
    function PNHotelImage($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel_image';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>