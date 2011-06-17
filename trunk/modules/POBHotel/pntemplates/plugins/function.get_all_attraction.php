<?php

function smarty_function_get_all_attraction($params, &$smarty) {

    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_attraction'];
    $column = $pntables['pobhotel_attraction_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]  
              FROM
                $table";

      $column = array("attraction_id","attraction_name");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('attractionArray', $objectArray); 

}