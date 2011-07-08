<?php
  class PNMember extends PNObject {
    function PNMember($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobmember_member';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
  }
?>