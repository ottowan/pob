<?php
  class PNHotelIndexPoint extends PNObject {
    function PNHotelIndexPoint($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_hotel_index_point';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
  }
?>