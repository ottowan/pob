<?php

function smarty_function_get_all_room($params, &$smarty) {

    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_room'];
    $column = $pntables['pobhotel_room_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]  
              FROM
                $table";

      $column = array("room_id","room_name");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('roomArray', $objectArray); 

}