<?php
  class PNRoomArray extends PNObjectArray {
    function PNRoomArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY room_id ASC';
     return $order;
    }

    function genFilter(){
      //implement code here
      $where = '';
      return $where;
    }
  }
?>