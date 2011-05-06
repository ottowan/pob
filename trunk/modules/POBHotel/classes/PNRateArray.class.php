<?php
  class PNRateArray extends PNObjectArray {
    function PNRateArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_rate';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY rate_id ASC';
     return $order;
    }
  }


?>