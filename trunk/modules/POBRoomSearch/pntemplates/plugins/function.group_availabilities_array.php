<?php

function smarty_function_group_availabilities_array($params, &$smarty) {
    //Get value from page
    $avail = $params['avail'];


    //pnShutDown();
    if($avail){
        $newArray = array();

      // Create a new array from the source array. 
      // We'll use the brand/model as a lookup.
      foreach($avail as $element) {
        $elementKey = $element['InvCode'];

        // Does this brand/model combo already exist?
        if(!isset($newArray[$elementKey])) {
            // No - create the new element.
          foreach($element as $key=>$item){
            $newArray[$elementKey][$key] = $item;
          }
        }else {
          $newArray[$elementKey]['Rate'] += $element['Rate'];
        }
      }
    }

    //var_dump($newArray);
    //print_r($newArray); exit;


    if ($newArray) {
        $smarty->assign('groupAvailabilities', $newArray); 
    } else {
        return "";
    }

}