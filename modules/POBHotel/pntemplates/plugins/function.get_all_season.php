<?php

function smarty_function_get_all_season($params, &$smarty) {
    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_season'];
    $column = $pntables['pobhotel_season_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]
              FROM
                $table";

      $column = array("season_id","season_name");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('seasonArray', $objectArray);
}