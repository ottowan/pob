<?php
  class PNFacility extends PNObject {
    function PNFacility($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_facility';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>