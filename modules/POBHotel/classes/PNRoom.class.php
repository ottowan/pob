<?php
class PNRoom extends PNObject {
    function PNRoom($init=null, $where='') {
      $this->PNObject();

      $this->_objType       = 'pobhotel_room';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_init($init, $where);
    }
    
    function insertPostProcess(){
      $this->updatePostCalendar("insertRoom");
    }
    
    function updatePostProcess(){
      $this->updatePostCalendar("updateRoom");
    }
    
    private function updatePostCalendar($event){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', 'GuestRoomType', false)))
        return LogUtil::registerError ('Unable to load class [GuestRoomType] ...');

      $object = new $class ();
      $object->get($this->_objData['guest_room_type_id']);
      
      $args = array(
        'id' => $this->_objData['id'],
        'name' => $this->_objData['name'],
        'description' => $this->_objData['description'],
        'guest_room_type_id' => $this->_objData['guest_room_type_id'],
        'guest_room_type_name' => $object->_objData["name"]
      );
      pnModAPIFunc('PostCalendar', 'user', $event, $args);
    }
}
?>