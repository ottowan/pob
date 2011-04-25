<?php
  class PNLocationCategoryArray extends PNObjectArray {
    function PNLocationCategoryArray($init=null, $where='') {
      $this->PNObject();
      $this->_objType       = 'pobhotel_location_category';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    }

    function genSort(){
      $order = ' ORDER BY location_category_id ASC';
     return $order;
    }
  }

?>