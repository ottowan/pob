<?php

function smarty_function_get_all_room($params, &$smarty) {
    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_room'];
    $column = $pntables['pobhotel_room_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[guest_room_type_id],
                $column[name],
                $column[description]
              FROM
                $table";

      $column = array("room_id","guest_room_type_id","room_name","room_description");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('roomArray', $objectArray);
}