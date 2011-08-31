<?php
class PNRoom extends PNObject {
    function PNRoom($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
}
?>