<?php
function POBPortal_search_searchResult(){

  $render = pnRender::getInstance('POBPortal');
  Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
    $getter = new POBReader("http://localhost/");
    $keyword = FormUtil::getPassedValue ('form', FALSE, 'REQUEST');
    //$keyword['search'] = "Ban";
    $result = $getter->searchHotel($keyword);
    if(count($result['data'])==19){
      $data['data'] = $result['data'];
    }else{
      $data = $result['data'];
    }
    $render->assign("totalItems",$data['totalItems']);
    $render->assign("totalPages",$data['totalPages']);
    $render->assign("nowPage",$data['nowPage']);
    $render->assign("next",$data['next']);
    $render->assign("previous",$data['previous']);
    
    $render->assign("data",$data);
    if(FormUtil::getPassedValue ('source', FALSE, 'REQUEST')){
      var_dump($result);
      exit;
    }
    return $render->fetch('user_list_hotel.htm');
}
?>