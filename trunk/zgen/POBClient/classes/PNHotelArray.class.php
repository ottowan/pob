<?php
  class PNHotelArray extends PNObjectArray {
    function PNHotel($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY _id ASC';
     return $order;
    }

    function genFilter(){
      //implement code here
      $where = '';
      return $where;
    }
  }
?>