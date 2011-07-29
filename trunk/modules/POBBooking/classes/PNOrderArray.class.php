<?php
class PNOrderArray extends PNObjectArray {
  function PNOrderArray($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_booking';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }
}
