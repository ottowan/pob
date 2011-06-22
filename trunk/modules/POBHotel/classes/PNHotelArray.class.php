<?php
  class PNHotelArray extends PNObjectArray {
    function PNHotelArray($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';

      $this->_objJoin[] = array ( 'join_table' => 'pobhotel_status',
                                  'join_field' => array('name'),
                                  'object_field_name' => array('status_name'),
                                  'compare_field_table' => 'status_id',
                                  'compare_field_join' => 'id');
                                  
                                  
      $this->_init($init, $where);
        
    }

    function genFilter(){

      $wheres = array();

      //Get filter by id
      $id = FormUtil::getPassedValue ('id', false);
      if($id){
        $where[] = " hotel_id = ".$id;
      }
      //Get filter by list
      $list = FormUtil::getPassedValue ('list', false);
      if($list){
        $where[] = " hotel_id IN (".$list.")";
      }
      
      $pntables = pnDBGetTables();
      $column = $pntables[$this->_objType.'_column'];
      $columnHotelLocation = $pntables['pobhotel_hotel_location_column'];
      $columnLocationCategory = $pntables['pobhotel_location_category_column'];

      
      $search = FormUtil::getPassedValue ('search', FALSE, 'REQUEST');
      $searchArray = explode(" ",$search);
      foreach($searchArray AS $value){
        $searchArray2[] = str_replace("+"," ", $value);
      }
      $searchImploded = implode(",",$searchArray2);
      if($search){
        foreach($searchArray2 AS $value){
          $whereSearch[] = " $column[name] LIKE '%".$value."%'";
          $whereSearch[] = " $column[descriptions] LIKE '%".$value."%'";
          $whereSearch[] = " $column[id] IN (SELECT $columnHotelLocation[hotel_id] FROM $pntables[pobhotel_hotel_location] WHERE $columnHotelLocation[location_category_id] = (SELECT $columnLocationCategory[id] FROM $pntables[pobhotel_location_category] WHERE $columnLocationCategory[name] LIKE '%".$value."%'))";
        }

        $wheres = implode(" OR ", $whereSearch);
        //var_dump($wheres);
        //exit;
        return $wheres;
      }
      
      
      
      
      
      $wheres = implode(" AND ", $where);
      return $wheres;
    }

    function genSort(){
      $pntables = pnDBGetTables();
      $column = $pntables[$this->_objType.'_column'];
  
      $desc  = FormUtil::getPassedValue ('desc', FALSE, 'REQUEST');
      $asc  = FormUtil::getPassedValue ('asc', FALSE, 'REQUEST');
      
      if($desc){
        //return " ORDER BY $column[$desc] desc";
        return " ORDER BY $column[$desc] DESC";
      }
      if($asc){
        //return " ORDER BY $column[$asc] asc";
        return " ORDER BY $column[$asc] ASC";
      }
      
      return ' ORDER BY hotel_id ASC';
    }
  }




?>