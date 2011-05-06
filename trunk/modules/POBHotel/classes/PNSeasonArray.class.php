<?php
  class PNSeasonArray extends PNObjectArray {
    function PNSeasonArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_season';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY season_id ASC';
     return $order;
    }
  }




?>