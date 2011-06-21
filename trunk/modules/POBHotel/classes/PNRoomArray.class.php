<?php
  class PNRoomArray extends PNObjectArray {
    function PNRoomArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      $this->_init($init, $where);
    }

    function genSort(){
      $order = ' ORDER BY room_id ASC';
     return $order;
    }
    function genFilterRoomListByHotelId(){
      $pntables = pnDBGetTables();
      $column  = $pntables[$this->_objType.'_column'];
      
      $id = FormUtil::getPassedValue ('id', FALSE, 'REQUEST');
      if($id){
        $wheres[] = " $column[hotel_id] = ".$id;
      }
      
  
      return implode(' AND ', $wheres) ;
    }
  }


?>