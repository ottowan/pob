<?php
class PNGuestRoomType extends PNObject {
    function PNGuestRoomType($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_guest_room_type';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
}
?>