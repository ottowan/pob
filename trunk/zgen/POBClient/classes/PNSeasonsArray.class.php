<?php
  class PNSeasonsArray extends PNObjectArray {
    function PNSeasons($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_seasons';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_hotel',
                              'join_field'          =>  array('name'),
                              'object_field_name'   =>  array('hotel_name'),
                              'compare_field_table' =>  'hotel_id',
                              'compare_field_join'  =>  'id');

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_room',
                              'join_field'          =>  array('name'),
                              'object_field_name'   =>  array('room_name'),
                              'compare_field_table' =>  'room_id',
                              'compare_field_join'  =>  'id');

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