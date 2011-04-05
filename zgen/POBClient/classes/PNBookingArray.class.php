<?php
  class PNBookingArray extends PNObjectArray {
    function PNBookingArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_booking';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_hotel',
                              'join_field'          =>  array('id', 'code', 'name' ),
                              'object_field_name'   =>  array('hotel_id','hotel_code','hotel_name'),
                              'compare_field_table' =>  'hotel_id',
                              'compare_field_join'  =>  'id');

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_room',
                              'join_field'          =>  array('id', 'name', 'price' ),
                              'object_field_name'   =>  array('room_id','room_name','room_price'),
                              'compare_field_table' =>  'room_id',
                              'compare_field_join'  =>  'id');

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_season',
                              'join_field'          =>  array('id', 'name' ),
                              'object_field_name'   =>  array('season_id','season_name'),
                              'compare_field_table' =>  'season_id',
                              'compare_field_join'  =>  'id');

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_customer',
                              'join_field'          =>  array('id', 'firstname', 'lastname' ),
                              'object_field_name'   =>  array('customer_id','customer_firstname','customer_lastname'),
                              'compare_field_table' =>  'customer_id',
                              'compare_field_join'  =>  'id');

      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY booking_id ASC';
     return $order;
    }

    function genFilter(){
      //implement code here
      $where = '';
      return $where;
    }
  }
?>