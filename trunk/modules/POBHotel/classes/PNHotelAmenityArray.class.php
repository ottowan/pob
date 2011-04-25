<?php
  class PNHotelAmenityArray extends PNObjectArray {
    function PNHotelAmenityArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel_amenity';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    function genSort(){
      $order = ' ORDER BY hotel_amenity_id ASC';
     return $order;
    }
  }




?>