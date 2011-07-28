<?php

function smarty_function_get_all_guest_room_type($params, &$smarty) {
    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_guest_room_type'];
    $column = $pntables['pobhotel_guest_room_type_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]
              FROM
                $table";

      $column = array("guest_room_type_id","guest_room_type_name","guest_room_type_description");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('guestRoomTypeArray', $objectArray);
}