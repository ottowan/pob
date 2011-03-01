<?php
  function pHCCP_init(){
    if (!DBUtil::createTable('phccp_room')) {
      return false;
    }
    if (!DBUtil::createTable('phccp_facility')) {
      return false;
    }
    return true;
  }

  function phccp_delete(){
    DBUtil::dropTable('phccp_room');
    DBUtil::dropTable('phccp_facility');
    return true;
  }
?>