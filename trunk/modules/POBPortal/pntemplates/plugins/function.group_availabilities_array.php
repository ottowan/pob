<?php

function smarty_function_group_availabilities_array($params, &$smarty) {
    //Get value from page
    $avail = $params['avail'];
    //print_r($avail); exit;

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
      //unset($newArray);
      $smartyArray = array();
      $i=0;
      foreach($newArray as $smartyKey=>$smartyItem){
        $smartyArray[$i++] = $smartyItem;
      }
    }

    //print_r($smartyArray); exit;
    if ($smartyArray) {
        $smarty->assign('groupAvailabilities', $smartyArray); 
    } else {
        return "";
    }

}