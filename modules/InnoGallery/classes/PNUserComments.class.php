<?php
class PNUserComments extends PNObjectEx {
  function PNUserComments($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'innogallery_comments';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

  function insertPreProcess(){
    Loader::loadClass('InnoUtil', "modules/InnoGallery/pnincludes");
    $this->_objData['ip'] = InnoUtil::getIpAddress();
  }

}