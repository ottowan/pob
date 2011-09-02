<?php
class PNDayBookingArray extends PNObjectArray {
  function PNDayBookingArray($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_daybooking';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

}
