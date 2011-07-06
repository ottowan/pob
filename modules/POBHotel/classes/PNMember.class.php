<?php
  class PNMember extends PNObject {
    function PNMember($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_member';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }


    function insertPostProcess(){
      $this->createDatabase();
    }


    private function createDatabase(){
      if (!($class = Loader::loadClass('SubdomainCreator', "modules/POBHotel/pnincludes")))
        return LogUtil::registerError ('Unable to load class [SubdomainCreator] ...');
      $form = FormUtil::getPassedValue ('form', false, 'REQUEST');
      $obj = new SubdomainCreator();
      $obj->makedb($form['database_name']);
      $obj->sqlDump();
      exit;
    }

  }
?>