<?php
  class PNStatusArray extends PNObjectArray {
    function PNStatusArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_status';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY status_id ASC';
     return $order;
    }
  }
?>