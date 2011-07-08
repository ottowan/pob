<?php

function smarty_function_get_userproperty($params, &$smarty) {
    //Get value from page
    //Check exist user & topic
    $pntables = pnDBGetTables();

    /////////////////////////////////////////////////////////////////
    //
    // 1st query
    //
    /////////////////////////////////////////////////////////////////
    //1st table use in query
    $tableUsers  = $pntables['users'];
    $columnUsers = $pntables['users_column'];

    $tableGroupMembership  = $pntables['group_membership'];
    $columnGroupMembership = $pntables['group_membership_column'];

    //1st query
    $sql = "SELECT
              $tableUsers.$columnUsers[uid],
              $tableUsers.$columnUsers[uname],
              $tableUsers.$columnUsers[pass],
              $tableUsers.$columnUsers[email]
            FROM
              $tableUsers, $tableGroupMembership
            WHERE
              $tableGroupMembership.$columnGroupMembership[gid] = 1
            AND
              $tableGroupMembership.$columnGroupMembership[uid] != 1
            AND
              $tableUsers.$columnUsers[uid] = $tableGroupMembership.$columnGroupMembership[uid]
            ";

    //echo $sql; exit;
    $column = array("uid", "uname", "pass","email");
    $result = DBUtil::executeSQL($sql);
    $objectUserArray = DBUtil::marshallObjects ($result, $column);
    //var_dump($objectUserArray); exit;

    /////////////////////////////////////////////////////////////////
    //
    // 2nd query
    //
    /////////////////////////////////////////////////////////////////
    //2nd table use in query
    $tableUserData  = $pntables['user_data'];
    $columnUserData = $pntables['user_data_column'];

    $tableUserProperty  = $pntables['user_property'];
    $columnUserProperty = $pntables['user_property_column'];

    $i = 0;
    foreach($objectUserArray as $userItem){
      //echo $item["uid"];
      
      $sql = "SELECT
                $tableUserProperty.$columnUserProperty[prop_label],
                $tableUserData.$columnUserData[uda_value]
              FROM
                $tableUserData, $tableUserProperty
              WHERE
                $tableUserData.$columnUserData[uda_propid] = $tableUserProperty.$columnUserProperty[prop_id]
              AND 
                $tableUserData.$columnUserData[uda_uid] = $userItem[uid]
              ";

      //echo $sql; exit;
      $column = array("label", "value");
      $result = DBUtil::executeSQL($sql);
      $objectUserPropertyArray = DBUtil::marshallObjects ($result, $column);

      //Re index array
      $objectArrayTemp[$i][] = array('uid' => $userItem[uid]);
      $objectArrayTemp[$i][] = array('uname' => $userItem[uname]);
      $objectArrayTemp[$i][] = array('pass' => $userItem[pass]);
      $objectArrayTemp[$i][] = array('email' => $userItem[email]);
      foreach($objectUserPropertyArray as $item){
        $objectArrayTemp[$i][] = array(strtolower($item['label']) => $item['value'] );
      }

      $i++;
    }

    //var_dump($objectArray); exit;

    //Converse array from 2D to 1D
    


    foreach($objectArrayTemp as $item){
      $objectArray[] = convertMultiDimensionToOneDomension($item);
    }
    //var_dump($objectArray); exit;
    if ($objectArray) {
        $smarty->assign('userArray', $objectArray); 
    } else {
        return "";
    }
}


function convertMultiDimensionToOneDomension(array $inputArray){
   $data = array();

   if (is_array($inputArray)) {
      foreach ($inputArray as $key => &$value) {
         if (!is_array($value)) {
            $data[$key] = $value;
         } else {
            $data = array_merge($data, convertMultiDimensionToOneDomension($value));
         }
      }
   }

   return $data;
}