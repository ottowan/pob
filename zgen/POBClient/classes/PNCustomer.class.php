<?php
  class PNCustomer extends PNObject {
    function PNCustomer($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_customer';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
    
  }
?>