<?php
  class PNContact extends PNObject {
    function PNContact($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
  }
?>