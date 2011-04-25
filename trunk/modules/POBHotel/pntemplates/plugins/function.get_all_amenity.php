<?php

function smarty_function_get_all_amenity($params, &$smarty) {

    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_amenity'];
    $column = $pntables['pobhotel_amenity_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]  
              FROM
                $table";

      $column = array("amenity_id","amenity_name");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('amenityArray', $objectArray); 

}