<?php
  class PNGuestRoomTypeArray extends PNObjectArray {
    function PNGuestRoomTypeArray($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_guest_room_type';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY guest_room_type_id ASC';
     return $order;
    }
  }


?>