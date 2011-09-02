<?php
class PNDayBooking extends PNObject {
  function PNDayBooking($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_daybooking';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

  function insertPostProcess(){


    }
  }