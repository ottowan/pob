<?php
  class PNMemberArray extends PNObjectArray {
    function PNMemberArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_member';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }



  }
?>