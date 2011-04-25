<?php

function smarty_function_get_all_location_category($params, &$smarty) {

    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_location_category'];
    $column = $pntables['pobhotel_location_category_column'];
//var_dump($column);exit;
      //Query data
      $sql = "SELECT
                $column[id],
                $column[name]  
              FROM
                $table";

      $column = array("location_category_id","location_category_name");

      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('locationCategoryArray', $objectArray); 

}