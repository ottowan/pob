<?php

function smarty_function_get_member_status_by_uid($params, &$smarty) {

    $uid = $params['uid'];

    if($uid){
      $pntables = pnDBGetTables();
      $tableMember  = $pntables['pobmember_member'];
      $columnMember = $pntables['pobmember_member_column'];
      $sql = "SELECT
                $tableMember.$columnMember[status]
              FROM
                $tableMember
              WHERE
                $tableMember.$columnMember[uid] = $uid
              ";
      $column = array("status");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $status = $objectArray['0']['status'];
       //echo "sql : ".$sql."<br>";
       //echo "status : ".$status;
    }
    //echo $uid.":".$status;
    if ($status) {
        $smarty->assign('status', $status); 
    } else {
        $smarty->assign('status', 2); 
    }

}