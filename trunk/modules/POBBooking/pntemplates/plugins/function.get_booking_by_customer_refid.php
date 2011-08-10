<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_get_booking_by_customer_refid ($params, &$smarty) {
    //Query data
    $pntables = pnDBGetTables();
    $bookingtable  = $pntables['pobbooking_booking'];
    $bookingcolumn = $pntables['pobbooking_booking_column'];
      $sql = "
              SELECT 
              $bookingcolumn[id], 
              $bookingcolumn[customer_refid], 
              $bookingcolumn[status_id], 
              $bookingcolumn[hotelname], 
              $bookingcolumn[checkin_date], 
              $bookingcolumn[checkout_date], 
              $bookingcolumn[night], 
              $bookingcolumn[amount_room], 
              $bookingcolumn[roomtype], 
              $bookingcolumn[adult], 
              $bookingcolumn[child], 
              $bookingcolumn[room_rate], 
              $bookingcolumn[room_rate_total]
            FROM 
              $bookingtable
            WHERE 
              $bookingcolumn[customer_refid] = '$params[cus_refid]'";

      $column = array('id', 
                      'customer_refid', 
                      'status_id', 
                      'hotelname', 
                      'checkin_date', 
                      'checkout_date', 
                      'night', 
                      'amount_room', 
                      'roomtype', 
                      'adult', 
                      'child', 
                      'room_rate', 
                      'room_rate_total');
      $result = DBUtil::executeSQL($sql);
      //var_dump($sql);
      //exit;

      //Initial data to Array
      $bookingArray = DBUtil::marshallObjects ($result, $column);

      $smarty->assign("bookingArray", $bookingArray);
      $smarty->assign("booking_id", $bookingArray[0]['id']);
      $smarty->assign("refid", $bookingArray[0]['customer_refid']);

      $smarty->assign("book_status_id", $bookingArray[0]['status_id']);
      $smarty->assign("checkin_date", $bookingArray[0]['checkin_date']);
      $smarty->assign("checkout_date", $bookingArray[0]['checkout_date']);
      $smarty->assign("amount_room", $bookingArray[0]['amount_room']);
      $smarty->assign("night", $bookingArray[0]['night']);

}