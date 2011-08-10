<?php
class PNBookingArray extends PNObjectArray {
  function PNBookingArray($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_booking';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

  function genFilter(){
    $status_id = FormUtil::getPassedValue ('status_id', false);
    $where = " ";
    if($status_id){
      $where = " boo_status_id = $status_id";
    }

    return $where;
  }

}
