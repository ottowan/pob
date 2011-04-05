<?php
  class PNHotel extends PNObject {
    function PNHotel($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobclient_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }
    
    function selectExtendResult(){
      $id = FormUtil::getPassedValue ('id', false );
      $result = array();
      if ($id){
        $result['room'] = DBUtil::selectObjectArray(
                                                     'pobclient_room', 
                                                     'WHERE room_hotel_id = '.$id , 
                                                      '', 
                                                      -1, 
                                                      -1,
                                                      '', 
                                                      null, 
                                                      null, 
                                                      array(
                                                            'id',
                                                            'name'
                                                            )
                                                      );
        $result['facility'] = DBUtil::selectObjectArray(
                                                     'pobclient_facility', 
                                                     'WHERE facility_hotel_id = '.$id , 
                                                      '', 
                                                      -1, 
                                                      -1,
                                                      '', 
                                                      null, 
                                                      null, 
                                                      array(
                                                            'id',
                                                            'name'
                                                            )
                                                      );
      }
      
      return $result;
    }
  }
?>