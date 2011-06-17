<?php

function smarty_function_get_all_index_point($params, &$smarty) {

    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_index_point'];
    $column = $pntables['pobhotel_index_point_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]  
              FROM
                $table";

      $column = array("index_point_id","index_point_name");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('index_pointArray', $objectArray); 

}