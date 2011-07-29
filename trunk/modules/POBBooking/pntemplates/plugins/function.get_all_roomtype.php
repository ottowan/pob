<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_get_all_roomtype ($params, &$smarty) 
{
    //Query data
    $pntables = pnDBGetTables();
    $sql = "
            SELECT 
              roo_id,
              roo_name
            FROM z_ihotel_roomtype";
    $column = array("id", "name");
    $result = DBUtil::executeSQL($sql);

    //echo $sql;
    //Initial data to Array
    $objectArray = DBUtil::marshallObjects ($result, $column);

    $smarty->assign("roomtypeArray", $objectArray);

    

}