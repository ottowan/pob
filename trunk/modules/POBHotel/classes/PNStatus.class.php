<?php
  class PNStatus extends PNObject {
    function PNStatus($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_status';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>