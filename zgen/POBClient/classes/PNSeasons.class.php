<?php
  class PNSeasons extends PNObject {
    function PNSeasons($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_seasons';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
  }
?>