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
function POBPortal_RecommendHotelMapblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function POBPortal_RecommendHotelMapblock_info()
{
    return array('text_type'      => 'Recommend Hotel On Map',
                 'module'         => 'POBPortal',
                 'text_type_long' => 'Show recommend hotels on map',
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
function POBPortal_RecommendHotelMapblock_display($blockinfo)
{
    $modname  = 'POBPortal';
    $class    = 'RecommendHotelMap';
    $itemLimit = 50;

    $vars = pnBlockVarsFromContent($blockinfo['content']);
    //get setting
    $limit = $vars['pagesize'];
    $offset = 0;

    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);


    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBPortal/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBPortal/pnlang/' . $lang );
    }else if (file_exists('modules/POBPortal/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBPortal/pnlang/eng' );
    }

    //load render
    $render = pnRender::getInstance($modname);

    ///////////////////////////////////////////////
    //
    // Search recommend hotel
    //
    ///////////////////////////////////////////////
    $location  = "phuket";
    $distance  = "50";
    $latitude  = "7.970838";
    $longitude = "98.329697";

    //Send param to HotelSearch service 
    Loader::loadClass('HotelLimitSearchEndpoint',"modules/POBPortal/pnincludes");
    $hotelLimitSearch = new HotelLimitSearchEndpoint();
    $hotelLimitSearch->setHotelLimitSearchXML( $location, $distance, $latitude, $longitude, $startDate, $endDate, $limit, $offset);

    //XML Response
    $response = $hotelLimitSearch->getHotelLimitSearchXML();
    //print($response); exit;

    $xmlObject = simplexml_load_string($response);
    //print_r($xmlObject); exit;

    $repackObjectArray = $hotelLimitSearch->repackObjectArrayForDisplay($xmlObject);

    $repackArray = $repackObjectArray["Properties"];
    //print_r($repackArray); exit;

    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBPortal/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBPortal/pnlang/' . $lang );
    }else if (file_exists('modules/POBPortal/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBPortal/pnlang/eng' );
    }


    $render->assign("startDate", $startDate );
    $render->assign("endDate", $endDate );
    $render->assign("Latitude", $latitude );
    $render->assign("Longitude", $longitude );
    $render->assign("objectArray", $repackArray );

    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_recommendhotelmap.htm');
    return themesideblock($blockinfo);
}
/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function POBPortal_RecommendHotelMapblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('POBPortal');
  $render->assign('form', $vars);
  
	return $render->fetch('block_recommendhotelmap_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function POBPortal_RecommendHotelMapblock_update($blockinfo)
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
    $pnRender->clear_cache('block_recommendhotelmap.htm');
    
    return $blockinfo;
}
