<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_get_total_price_by_refid ($params, &$smarty) {
    //Query data
    $pntables = pnDBGetTables();
    $bookingtable  = $pntables['pobbooking_booking'];
    $bookingcolumn = $pntables['pobbooking_booking_column'];
      $sql = "
              SELECT 
                  DISTINCT  $bookingcolumn[room_rate_total] as total_price
              FROM 
                  $bookingtable
              WHERE 
                  $bookingcolumn[customer_refid] = '$params[refid]' ";

      $column = array("total_price");
      $result = DBUtil::executeSQL($sql);

      //echo $sql;
      //Initial data to Array
      $objectArray = DBUtil::marshallObjects ($result, $column);

      echo isset($objectArray[0][total_price])? $objectArray[0][total_price] : "0";
}