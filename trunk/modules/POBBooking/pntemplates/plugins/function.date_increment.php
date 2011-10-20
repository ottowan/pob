<?php

function smarty_function_date_increment($params, &$smarty) {
   $date = $params['date'];
   $add = $params['add'];
   //$assign = null;
   $dateaddstr = strtotime("+$add day", strtotime($date));
   $dateadd = date("Y-m-d", $dateaddstr);
   //return $dateadd;
   $smarty->assign("date_inc",$dateadd);
}
?> 