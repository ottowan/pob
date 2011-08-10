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
function POBHotel_FourImageblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function POBHotel_FourImageblock_info()
{ 
    return array('text_type'      => 'FourImage',
                 'module'         => 'POBHotel',
                 'text_type_long' => '4 images block',
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
function POBHotel_FourImageblock_display($blockinfo)
{
    $modname  = 'POBHotel';
    $class    = 'FourImage';


    $vars = pnBlockVarsFromContent($blockinfo['content']);
    //get setting
    $render = pnRender::getInstance($modname);
    $imageRows = DBUtil::selectObjectCount( "pobhotel_hotel_image");

    if((int)$imageRows > 0){
      if (!($class = Loader::loadClassFromModule ('POBHotel',$class, true)))
        return LogUtil::registerError ("Unable to load class [$ctrl] ...");
      //var_dump($class); exit;
      $objectArray = new $class ();

      $objectArray->get ($where, ' ORDER BY hotel_image_id DESC ' , $offset, 4);

      $render = pnRender::getInstance($modname);

      //assign to view
      $render->assign('objectArray', $objectArray->_objData);
    }
    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_fourimage.htm');
    return themesideblock($blockinfo);
}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function POBHotel_HotelFourImageblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('POBHotel');
  $render->assign('form', $vars);
  
	return $render->fetch('block_fourimage_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function POBHotel_FourImageblock_update($blockinfo)
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
    $pnRender->clear_cache('block_fourimage.htm');
    
    return $blockinfo;
}
