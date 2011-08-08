<?php
  class PNGalleryArray extends PNObjectArray {
    function PNGalleryArray($init=null, $where='') {
      $this->PNObjectArray();
    
      $this->_objType       = 'pobhotel_hotel_image';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
  }
?>