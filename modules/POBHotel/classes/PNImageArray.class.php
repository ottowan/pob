<?php
  class PNHotelArray extends PNObjectArray {
    function PNHotelArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_status',
                                  'join_field' => array('name'),
                                  'object_field_name' => array('status_name'),
                                  'compare_field_table' => 'status_id',
                                  'compare_field_join' => 'id');

      $this->_init($init, $where);

    }

    function genSort(){
      $order = ' ORDER BY hotel_id ASC';
     return $order;
    }

  }




?>