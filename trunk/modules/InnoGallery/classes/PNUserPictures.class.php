<?php
class PNUserPictures extends PNObjectEx {
  function PNUserPictures($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'innogallery_pictures';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

}