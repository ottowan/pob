<?php


Loader::loadClass('DateUtilEx', "modules/POBPortal/pnincludes");
Loader::loadClass('ObjectUtilEx', "modules/POBPortal/pnincludes");
Loader::loadClass('PNObjectEx', "modules/POBPortal/pnincludes");
Loader::loadClass('PNObjectExArray', "modules/POBPortal/pnincludes");
Loader::loadClass('DBUtilEx', "modules/POBPortal/pnincludes");
Loader::loadClass('SecurityUtilEx', "modules/POBPortal/pnincludes");
Loader::loadClass('InnoUtil', "modules/POBPortal/pnincludes");

/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function POBPortal_RecommendHotelblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function POBPortal_RecommendHotelblock_info()
{
    return array('text_type'      => 'Recommend Hotel',
                 'module'         => 'POBPortal',
                 'text_type_long' => 'Show recommend hotels',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function POBPortal_RecommendHotelblock_display($blockinfo)
{
    $modname  = 'POBPortal';
    $class    = 'RecommendHotel';


    $vars = pnBlockVarsFromContent($blockinfo['content']);
    //get setting
    $pagesize = $vars['pagesize'];

    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);
    //load render
    $render = pnRender::getInstance($modname);

    $endpoint = 'http://localhost/zk/index.php?module=POBHotel&type=ws&func=getHotelList&list=1,2,3,4&desc=id';
    //$objectArray = 

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
    $response = simplexml_load_string($response);

    $objectArray = xmltoArray( $response);
    var_dump($objectArray);
    $render->assign('objectArray', $objectArray);

    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_recommendhotel.htm');
    return themesideblock($blockinfo);
}


function xmltoArray($obj, $level=0) {

  //var_dump($obj); exit;
  $items = array();

  if(!is_object($obj)) return $items;
    
  $child = (array)$obj;

  if(sizeof($child)>1) {
    foreach($child as $aa=>$bb) {
        if(is_array($bb)) {
      foreach($bb as $ee=>$ff) {
          if(!is_object($ff)) {
        $items[$aa][$ee] = $ff;
          } else
          if(get_class($ff)=='SimpleXMLElement') {
        $items[$aa][$ee] = xmltoArray($ff,$level+1);
          }
      }
        } else
        if(!is_object($bb)) {
      $items[$aa] = $bb;
        } else
        if(get_class($bb)=='SimpleXMLElement') {
      $items[$aa] = xmltoArray($bb,$level+1);
        }
    }
  } else
  if(sizeof($child)>0) {
    foreach($child as $aa=>$bb) {
        if(!is_array($bb)&&!is_object($bb)) {
      $items[$aa] = $bb;
        } else
        if(is_object($bb)) {
      $items[$aa] = xmltoArray($bb,$level+1);
        } else {
      foreach($bb as $cc=>$dd) {
          if(!is_object($dd)) {
        $items[$obj->getName()][$cc] = $dd;
          } else
          if(get_class($dd)=='SimpleXMLElement') {
        $items[$obj->getName()][$cc] = xmltoArray($dd,$level+1);
          }
      }
        }
    }
  }
  return $items;
}

/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function POBPortal_RecommendHotelblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('POBPortal');
  $render->assign('form', $vars);
  
	return $render->fetch('block_recommendhotel_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function POBPortal_RecommendHotelblock_update($blockinfo)
{
    
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    // alter the corresponding variable
    $form = FormUtil::getPassedValue ('form', false );
    $vars['pagesize'] = !empty($form['pagesize']) ? $form['pagesize'] : 10;

    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    // clear the block cache
    $pnRender = pnRender::getInstance('POBPortal');
    $pnRender->clear_cache('block_recommendhotel.htm');
    
    return $blockinfo;
}
