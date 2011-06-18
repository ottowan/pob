<?php
  class PNHotelLocationArray extends PNObjectArray {
    function PNHotelLocationArray($init=null, $where='') {
      $this->PNObject();
      $this->_objType       = 'pobhotel_hotel_location';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      
      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_location_category',
                                  'compare_field_table' => 'location_category_id',
                                  'compare_field_join' => 'id',
                                  'join_field' => array('id','name'),
                                  'object_field_name' => array('location_id','location_name'));
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY location_id ASC';
     return $order;
    }
  }

?>