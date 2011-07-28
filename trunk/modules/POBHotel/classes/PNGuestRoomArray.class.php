<?php
  class PNGuestRoomArray extends PNObjectArray {
    function PNGuestRoomArray($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_guest_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY guest_room_id ASC';
     return $order;
    }

  }


?>