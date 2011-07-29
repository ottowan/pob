<?php
class PNCustomerArray extends PNObjectArray {
  function PNCustomerArray($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_customer';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

  function genFilter(){
    $status_id = FormUtil::getPassedValue ('status_id', false);
    $where = " ";
    if($status_id){
      $where = " cus_status_id = $status_id";
    }

    return $where;
  }
}
