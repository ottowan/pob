<?php
  class PNHotel extends PNObject {
    function PNHotel($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>