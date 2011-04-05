<?php
  class PNCustomerArray extends PNObjectArray {
    function PNCustomerArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_customer';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY customer_id ASC';
     return $order;
    }

    function genFilter(){
      //implement code here
      $where = '';
      return $where;
    }
  }
?>