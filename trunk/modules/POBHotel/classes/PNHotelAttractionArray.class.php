<?php
  class PNHotelAttractionArray extends PNObjectArray {
    function PNHotelAttractionArray($init=null, $where='') {
      $this->PNObject();
      $this->_objType       = 'pobhotel_hotel_attraction';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_attraction',
                                  'compare_field_table' => 'attraction_id',
                                  'compare_field_join' => 'id',
                                  'join_field' => array('id','name'),
                                  'object_field_name' => array('attraction_id','attraction_name'));
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY hotel_attraction_id ASC';
     return $order;
    }
  }

?>