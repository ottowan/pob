<?php
  class PNHotelMap extends PNObject {
    function PNHotelMap($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
  }
?>