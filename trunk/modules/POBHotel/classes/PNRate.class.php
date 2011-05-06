<?php
  class PNRate extends PNObject {
    function PNRate($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_rate';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
      $this->_init($init, $where);
    }
    function selectExtendResult(){
      $id = FormUtil::getPassedValue ('id', false);
      $result = array();
      if ($id){

        $fieldArray = array('room_id');

        $result['room'] = DBUtil::selectObjectArray( 'pobhotel_room',
                                                                  "WHERE room_id = '$id'",
                                                                  '',
                                                                  -1,
                                                                  -1,
                                                                  '',
                                                                  null,
                                                                  null,
                                                                  $fieldArray
        );
      return $result;
    }
  }
}
?>