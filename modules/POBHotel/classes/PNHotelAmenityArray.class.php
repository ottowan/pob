<?php
  class PNHotelAmenityArray extends PNObjectArray {
    function PNHotelAmenityArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel_amenity';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      
      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_amenity',
                            'compare_field_table' => 'amenity_id',
                            'compare_field_join' => 'id',
                            'join_field' => array('id','name'),
                            'object_field_name' => array('amenity_id','amenity_name'));
                            
      $this->_init($init, $where);
    }
    function genSort(){
      $order = ' ORDER BY hotel_amenity_id ASC';
     return $order;
    }
  }




?>