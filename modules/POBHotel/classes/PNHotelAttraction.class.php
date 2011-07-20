<?php
  class PNHotelAttraction extends PNObject {
    function PNHotelAttraction($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_hotel_attraction';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
  }
?>