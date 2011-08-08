<?php
  class PNFacilityArray extends PNObjectArray {
    function PNFacilityArray($init=null, $where='') {
      $this->PNObjectArray();
    
      $this->_objType       = 'pobhotel_hotel_amenity';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_amenity',
                                  'compare_field_table' => 'amenity_id',
                                  'compare_field_join' => 'id',
                                  'join_field' => array('name'),
                                  'object_field_name' => array('amenity_name')
                          );


      $this->_init($init, $where);
    }
  }
?>