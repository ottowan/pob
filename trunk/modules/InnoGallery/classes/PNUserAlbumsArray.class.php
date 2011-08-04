<?php
class PNUserAlbumsArray extends PNObjectExArray {
  function PNUserAlbumsArray($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'innogallery_albums';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';
    
    $this->_objSort       = "ORDER BY abm_id DESC";
    $this->_init($init, $where);
  }


}