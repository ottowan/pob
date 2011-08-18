<?php

function smarty_function_get_all_hotel_images($params, &$smarty) {

    $pntables = pnDBGetTables();
    $table  = $pntables['pobhotel_hotel_image'];
    $column = $pntables['pobhotel_hotel_image_column'];

      //Query data
      $sql = "SELECT
                $column[id],
                $column[filename],
                $column[filepath],
                $column[thumbname],
                $column[thumbpath]
              FROM
                $table";

      $column = array("images_id","images_filename","images_filepath","images_thumbname","images_thumbpath");
      $result = DBUtil::executeSQL($sql);
      $objectArray = DBUtil::marshallObjects ($result, $column);
      $smarty->assign('imagesArray', $objectArray);

}