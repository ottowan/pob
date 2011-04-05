<?php
  class PNFacilityArray extends PNObjectArray {
    function PNFacilityArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_facility';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[]     = array ( 'join_table'  =>  'pobclient_hotel',
                              'join_field'          =>  array('code', 'name' ),
                              'object_field_name'   =>  array('hotel_code','hotel_name'),
                              'compare_field_table' =>  'hotel_id',
                              'compare_field_join'  =>  'id');

      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY facility_id ASC';
     return $order;
    }

    function genFilter(){
      //implement code here
      $where = '';
      return $where;
    }
  }
?>