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
    $itemLimit = 5;

    $vars = pnBlockVarsFromContent($blockinfo['content']);
    //get setting
    $limit = $vars['pagesize'];
    $offset = 0;

    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);

    //load render
    $render = pnRender::getInstance($modname);

    ///////////////////////////////////////////////
    //
    // Search recommend hotel
    //
    ///////////////////////////////////////////////
    $location  = "phuket";
    $distance  = "10";
    $latitude  = "7.88806";
    $longitude = "98.3975";

    //Send param to HotelSearch service 
    Loader::loadClass('HotelLimitSearchEndpoint',"modules/POBPortal/pnincludes");
    $hotelLimitSearch = new HotelLimitSearchEndpoint();
    $hotelLimitSearch->setHotelLimitSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate, $limit, $offset);

    //XML Response
    $response = $hotelLimitSearch->getHotelLimitSearchXML();
    //print($response); exit;

    //Convert xml response to array
    Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
    $reader = new POBReader();
    $arrayResponse = $reader->xmlToArray($response);
    $arrayResponse["startDate"] = $startDate;
    $arrayResponse["endDate"] = $endDate;

    $repackArray = array();
    $repackArray = $hotelLimitSearch->repackArrayLimitForDisplay($arrayResponse);

    $render->assign("objectArray",$repackArray);

    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_recommendhotel.htm');
    return themesideblock($blockinfo);
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