<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_count_order_unpaid ($params, &$smarty) {
    //Query data
    $pntables = pnDBGetTables();
    $custable  = $pntables['pobbooking_customer'];
    $cuscolumn = $pntables['pobbooking_customer_column'];
    $sql = "
            SELECT 
              COUNT( DISTINCT  $cuscolumn[id] ) as booking_count
            FROM 
              $custable
            WHERE 
              $cuscolumn[status_id] = 3 ";

      $column = array("booking_count");
      $result = DBUtil::executeSQL($sql);

      //echo $sql;
      //Initial data to Array
      $objectArray = DBUtil::marshallObjects ($result, $column);

      echo isset($objectArray[0][booking_count])? $objectArray[0][booking_count] : "0";

}