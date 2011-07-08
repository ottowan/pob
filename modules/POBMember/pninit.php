<?php
  function POBMember_init(){
    if (!DBUtil::createTable('POBMember_member')) {
      return false;
    }
    return true;
  }

  function POBMember_delete(){
    DBUtil::dropTable('POBMember_member');

    return true;
  }


?>