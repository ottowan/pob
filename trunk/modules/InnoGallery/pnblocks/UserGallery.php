<?php
Loader::loadFile('config.php', "modules/InnoGallery");

Loader::loadClass('SecurityUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('DateUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('ObjectUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('PNObjectEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('PNObjectExArray', "modules/InnoGallery/pnincludes");
Loader::loadClass('DBUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('InnoUtil', "modules/InnoGallery/pnincludes");

/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function InnoGallery_UserGalleryblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function InnoGallery_UserGalleryblock_info()
{
    return array('text_type'      => 'User Gallery',
                 'module'         => 'InnoGallery',
                 'text_type_long' => 'Show random image of InnoGallery',
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
function InnoGallery_UserGalleryblock_display($blockinfo)
{

    $modname  = 'InnoGallery';
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);
    //load render
    $render = pnRender::getInstance($modname);
    $render->assign('modulepath', 'modules/' . $modname);
    $render->assign('vars',$vars);
    /*
    $object = DBUtil::selectFieldArray ('innogallery_albums', 
                                      'id', 
                                      'WHERE abm_count_pictures > 0 AND abm_is_show = 1',
                                      'RAND()');
    $render->assign('albums_id',$object[0]);
    */
    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_usergallery.htm');
    return themesideblock($blockinfo);

}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function InnoGallery_UserGalleryblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('InnoGallery');
  $render->assign('form', $vars);
  
  return $render->fetch('block_usergallery_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function InnoGallery_UserGalleryblock_update($blockinfo)
{
    
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    // alter the corresponding variable
    $form = FormUtil::getPassedValue ('form', false );
    $vars['albums_id'] = !empty($form['albums_id']) ? $form['albums_id'] : 1;
    $vars['width']     = !empty($form['width']) ? $form['width'] : '345';
    $vars['height']    = !empty($form['height']) ? $form['height'] : '230';


    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    // clear the block cache
    $pnRender = pnRender::getInstance('InnoGallery');
    $pnRender->clear_cache('block_usergallery.htm');
    
    return $blockinfo;
}
