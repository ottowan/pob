<?php
class PNBooking extends PNObject {
  function PNBooking($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_booking';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

  function insertPostProcess(){


    }
  }