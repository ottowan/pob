<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_get_total_price_by_refid ($params, &$smarty) {
    //Query data
    $pntables = pnDBGetTables();
      $sql = "
              SELECT 
                DISTINCT  boo_total_price as total_price
            FROM 
              $pntables[pobbooking_booking]
            WHERE 
                boo_refid = '$params[refid]' ";

      $column = array("total_price");
      $result = DBUtil::executeSQL($sql);

      //echo $sql;
      //Initial data to Array
      $objectArray = DBUtil::marshallObjects ($result, $column);

      echo isset($objectArray[0][total_price])? $objectArray[0][total_price] : "0";
}