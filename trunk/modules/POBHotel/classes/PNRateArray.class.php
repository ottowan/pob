<?php
  class PNRateArray extends PNObjectArray {
    function PNRateArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_guest_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_guest_room_type',
                                  'compare_field_table' => 'type_id',
                                  'compare_field_join' => 'id',
                                  'join_field' => array('name'),
                                  'object_field_name' => array('roomtype_name')
                          );

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_season',
                                  'compare_field_table' => 'season_id',
                                  'compare_field_join' => 'id',
                                  'join_field' => array('name', 'date_start', 'date_end'),
                                  'object_field_name' => array('season_name', 'season_date_start', 'season_date_end')
                          );
      $this->_init($init, $where);
    }

  }


?>