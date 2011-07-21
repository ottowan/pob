<?php
  function POBMember_init(){
    if (!DBUtil::createTable('pobmember_member')) {
      return false;
    }
    return true;
  }

  function POBMember_delete(){
    DBUtil::dropTable('pobmember_member');

    return true;
  }


?>