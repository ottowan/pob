<?php

Loader::loadClass('DateUtilEx', "modules/POBHotel/pnincludes");
Loader::loadClass('ObjectUtilEx', "modules/POBHotel/pnincludes");
Loader::loadClass('PNObjectEx', "modules/POBHotel/pnincludes");
Loader::loadClass('PNObjectExArray', "modules/POBHotel/pnincludes");
Loader::loadClass('DBUtilEx', "modules/POBHotel/pnincludes");
Loader::loadClass('SecurityUtilEx', "modules/POBHotel/pnincludes");
Loader::loadClass('InnoUtil', "modules/POBHotel/pnincludes");
/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function POBHotel_HotelMapblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function POBHotel_HotelMapblock_info()
{ 
    return array('text_type'      => 'HotelMap',
                 'module'         => 'POBHotel',
                 'text_type_long' => 'map for hotel',
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
function POBHotel_HotelMapblock_display($blockinfo)
{
    $modname  = 'POBHotel';
    $class    = 'HotelMap';
    $id = 1;

    $vars = pnBlockVarsFromContent($blockinfo['content']);
    //get setting
    $pagesize = $vars['pagesize'];

    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);
    //load render
    $render = pnRender::getInstance($modname);

    if ($id){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel',  ucfirst($ctrl), false))){
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
      }
      $object  = new $class ();

      $object->get($id);

      $render->assign ('view', $object->_objData);
    }



    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_hotelmap.htm');
    return themesideblock($blockinfo);
}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function POBHotel_HotelMapblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('POBHotel');
  $render->assign('form', $vars);
  
	return $render->fetch('block_hotelmap_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function POBHotel_HotelMapblock_update($blockinfo)
{
    
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    // alter the corresponding variable
    $form = FormUtil::getPassedValue ('form', false );
    $vars['pagesize'] = !empty($form['pagesize']) ? $form['pagesize'] : 10;

    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    // clear the block cache
    $pnRender = pnRender::getInstance('POBHotel');
    $pnRender->clear_cache('block_hotelmap.htm');
    
    return $blockinfo;
}
