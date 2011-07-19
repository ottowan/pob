<?php
function POBPortal_search_searchResult(){

  $render = pnRender::getInstance('POBPortal');
  Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
  $getter = new POBReader();
  $keyword = FormUtil::getPassedValue ('form', FALSE, 'REQUEST');
  
  if(!$keyword){
    $keyword['search'] = FormUtil::getPassedValue ('search', FALSE, 'REQUEST');
    $keyword['page'] = FormUtil::getPassedValue ('page', 1, 'REQUEST');
  }
  //$keyword['search'] = "Ban";
  $result = $getter->searchHotel($keyword);
  if(count($result['data'])==19){
    $data['data'] = $result['data'];
  }else{
    $data = $result['data'];
  }
  $render->assign("totalItems",$result['totalItems']);
  $render->assign("totalPages",$result['totalPages']);
  $render->assign("nowPage",$result['nowPage']);
  $render->assign("next",$result['next']);
  $render->assign("previous",$result['previous']);
  
  $render->assign("data",$data);
  if(FormUtil::getPassedValue ('source', FALSE, 'REQUEST')){
    var_dump($result);
    var_dump($keyword);
    exit;
  }
  return $render->fetch('user_list_hotel.htm');
}
?>