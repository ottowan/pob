<?php
  class PNHotelIndexPointArray extends PNObjectArray {
    function PNHotelIndexPointArray($init=null, $where='') {
      $this->PNObject();
      $this->_objType       = 'pobhotel_hotel_index_point';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_index_point',
                                  'compare_field_table' => 'index_point_id',
                                  'compare_field_join' => 'id',
                                  'join_field' => array('id','name'),
                                  'object_field_name' => array('index_point_id','index_point_name'));
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY hotel_index_point_id ASC';
     return $order;
    }
  }

?>