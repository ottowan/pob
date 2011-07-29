<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_count_order_refund ($params, &$smarty) {
    //Query data
    $pntables = pnDBGetTables();
      $sql = "
              SELECT 
                COUNT( DISTINCT  boo_refid) as booking_count
            FROM 
              $pntables[pobbooking_booking]
            WHERE 
                boo_status_id = 4 ";

      $column = array("booking_count");
      $result = DBUtil::executeSQL($sql);

      //echo $sql;
      //Initial data to Array
      $objectArray = DBUtil::marshallObjects ($result, $column);

      echo isset($objectArray[0][booking_count])? $objectArray[0][booking_count] : "0";
}