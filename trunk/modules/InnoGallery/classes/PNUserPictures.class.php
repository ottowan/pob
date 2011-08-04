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

  function deletePostProcess(){
    //1.delete comment
    //2.delete files large
    //2.delete files small
  }
}