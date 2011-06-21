<?php
  class PNHotelImageArray extends PNObjectArray {
    function PNHotelImageArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel_image';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);

    }

    function genSort(){
      $order = ' ORDER BY id ASC';
     return $order;
    }
    

  }




?>